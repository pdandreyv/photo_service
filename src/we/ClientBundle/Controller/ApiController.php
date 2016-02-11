<?php

namespace we\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use we\ClientBundle\Entity\Photo;
use we\ClientBundle\Entity\Tag;

class ApiController extends Controller
{
    private function getEm(){
        return $this->getDoctrine()->getEntityManager();
    }
    
    public function indexAction()
    {
        return $this->render('weClientBundle:Default:index.html.twig');
    }
    
    /**
     * @Route("/api/send_photo")
     * @Method({"POST"})
     * @ApiDoc(
     *  description="Send photo with tags",
     *  input="we\ClientBundle\Form\SendPhotoType"
     * )
     */
    public function sendPhotoAction(Request $request)
    {
        $post = $request->request->all();
        $tags = $post['photo']['tags'];
        $file = isset($_FILES['photo']) ? $_FILES['photo'] : array();
        $errors = $this->checkFile($file);
        
        // upload file
        if(count($errors) === 0) {
            $filenameOrErrors = $this->uploadFile($file);
            if(is_array($filenameOrErrors))
                $errors = $this->uploadFile($file);
            else
                $filename = $filenameOrErrors;
        }
        if(count($errors) === 0 && !empty($filename)) {
            $photo = new Photo();
            $photo->setFilename($filename);

            $this->getEm()->persist($photo);
            $this->getEm()->flush();
            
            if(!empty($tags)) 
                $this->setTags($tags,$photo);
        }
        if(count($errors)) echo json_encode($errors);
        else echo "OK";
        
        exit;
    }
    
    protected function setTags($tags,$photo)
    {
        $tags = array_map('trim',explode(',', $tags));
        foreach($tags as $value){
            $tag = new Tag();
            $tag->setValue($value)
                ->setPhoto($photo);
            $this->getEm()->persist($tag);
            $this->getEm()->flush();
        }
    }
    
    public function checkFile(array $file, array $validation = NULL) {
        $defaultValidation = array(
            'size' => 2097152, //2M
            'type' => array(
                'jpg'   => 'image/jpeg',
                'png'   => 'image/png',
                'jpeg'  => 'image/jpeg',
                'gif'   => 'image/gif',
            ),
        );
        $errors = array();

        if(is_array($validation)) {
            $validation = array_merge_recursive($defaultValidation, $validation);
        } else {
            $validation = $defaultValidation;
        }

        // is empty?
        if(!isset($file['name']) ||
            !isset($file['type']) ||
            !isset($file['tmp_name']) ||
            !isset($file['size'])) {
            $errors[] = 'File is empty';
        }

        if($file['error']['photo']!=0) {
            $errors[] = "Error loading, code: ".print_r($file['error']['photo'],1);
        }

        // check size
        if(count($errors) === 0) {
            if((int)$file['size'] > $validation['size'])
                $errors[] = 'Large file size';
        }

        // check type
        if(count($errors) === 0) {
            $isChecked = false;
            foreach($validation['type'] as $type => $miniType) {
                if($file['type']['photo'] == $miniType)
                    $isChecked = true;
            }

            if(false === $isChecked)
                $errors[] = 'Type does not match';
        }

        return $errors;
    }
    public function getUploadDir($full=true){
        $rootDir = $this->get('kernel')->getRootDir() . '/../';
        $uploadDir = $rootDir . 'web/uploads';

        // create directory
        if(!file_exists($uploadDir))
            mkdir($uploadDir, 0777);
        
        return ($full)?$uploadDir:($rootDir.'web');
    }
    public function uploadFile(array $file) {
        $errors = array();
        $uploadDir = $this->getUploadDir();

        $ext = pathinfo($file['name']['photo']);
        $ext = $ext['extension'];
        $uploadfile = $uploadDir . md5($file['name']['photo'] . time()) . '.' . $ext;

        if(!move_uploaded_file($file['tmp_name']['photo'], $uploadfile))
            $errors[] = 'Error loading';

        $uploadfile = str_replace($this->getUploadDir(false), '', $uploadfile);

        return count($errors) === 0 ? $uploadfile : $errors;
    }
    /**
     * @Route("/api/del_photo")
     * @Method({"POST"})
     * @ApiDoc(
     *  description="Delete photo",
     *  input="we\ClientBundle\Form\DeleteType"
     * )
     */
    public function deletePhotoAction(Request $request)
    {
        $post = $request->request->all();
        $id = $post['data']['id'];
        $obj = $this->getEm()->getRepository('weClientBundle:Photo')->find($id);
        if($obj){
            $filename = $this->getUploadDir(false).$obj->getFilename();
            if(file_exists($filename)){
                unlink($filename);
            }
            $this->getEm()->remove($obj);
            $this->getEm()->flush();
            echo "OK";
        } else {
            echo 'Photo not found.';
        }
        exit;
    }
    /**
     * @Route("/api/del_tag")
     * @Method({"POST"})
     * @ApiDoc(
     *  description="Delete tag",
     *  input="we\ClientBundle\Form\DeleteType"
     * )
     */
    public function deleteTagAction(Request $request)
    {
        $post = $request->request->all();
        $id = $post['data']['id'];
        $obj = $this->getEm()->getRepository('weClientBundle:Tag')->find($id);
        if($obj){
            $this->getEm()->remove($obj);
            $this->getEm()->flush();
            echo "OK";
        } else {
            echo 'Tag not found.';
        }
        exit;
    }
    /**
     * @Route("/api/add_tag")
     * @Method({"POST"})
     * @ApiDoc(
     *  description="Add tag",
     *  input="we\ClientBundle\Form\AddTagType"
     * )
     */
    public function addTagAction(Request $request)
    {
        $post = $request->request->all();
        $photo_id = $post['tag']['photo_id'];
        $value = $post['tag']['value'];
        
        $photo = $this->getEm()->getRepository('weClientBundle:Photo')->find($photo_id);
        if($photo){
            $tag = new Tag();
            $tag->setValue($value)
                ->setPhoto($photo);
            $this->getEm()->persist($tag);
            $this->getEm()->flush();
            echo "OK";
        } else {
            echo 'Photo not found.';
        }
        exit;
    }
}
