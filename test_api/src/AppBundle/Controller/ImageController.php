<?php
// src/AppBundle/Controller/ImageController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\ImageDocument;
class ImageController extends Controller
{
	/**
     * @Route("/image/add")
     */
    public function addImageAction()
    {
		//print_r(($_POST));exit;
		if(isset($_POST['id']) && isset($_FILES['file']) && isset($_POST['label']) && $_POST['label'] != "" && $_POST['id'] == 0 ){
			$label = $_POST['label'];
			if(isset($_FILES['file'])){
				$fileName = ImageDocument::SaveFile();
			}
			$imageobj = new ImageDocument();
			$imageobj->setLabel($label);
			$imageobj->setFileName($fileName);

			$em = $this->getDoctrine()->getManager();

			$em->persist($imageobj);
			$em->flush();
			
			$result = ImageDocument::fetchImages();
			return new Response(json_encode([ 'status' => 'success', 'SuccessMessage' => 'Image Added Successfully', 'data' => $result, 'Success_Token' => '101']));
			
		} 
		else if(isset($_POST['id']) && $_POST['id'] != 0 && isset($_POST['label']) && $_POST['label'] != ""){

			$id = $_POST['id'];
			$label = $_POST['label'];
			$fileName = '';
			if(isset($_FILES['file'])){
				$fileName = ImageDocument::SaveFile();
			}
			$repository = $this->getDoctrine()
			->getRepository('AppBundle:ImageDocument');
			$imageobj = $repository->find($id);
			if($imageobj){
				$imageobj->setLabel($label);
				if($fileName != ''){
					$imageobj->setFileName($fileName);
				}
				
				$em = $this->getDoctrine()->getManager();
				$em->persist($imageobj);
				$em->flush();
				
				$result = ImageDocument::fetchImages();
				return new Response(json_encode([ 'status' => 'success', 'SuccessMessage' => 'Image Updated Successfully', 'data' => $result, 'Success_Token' => '101']));

			}
			
		} else{
			return new Response(json_encode([ 'status' => 'error', 'ErrorMessage' => 'Image and Image label are required', 'Error_Token' => '102']));

		}
    }
	
	/**
     * @Route("/image/delete")
     */
	public function deleteImageAction()
    {
		$id = $_POST['id'];
		$repository = $this->getDoctrine()
			->getRepository('AppBundle:ImageDocument');
		$item_to_delete = $repository->find($id);
		$dm = $this->getDoctrine()->getManager();
		$dm->remove($item_to_delete);
		$dm->flush();
        
		$result = ImageDocument::fetchImages();
		return new Response(json_encode([ 'status' => 'success', 'SuccessMessage' => 'Images Deleted Successfully','data' => $result, 'Success_Token' => '101']));

		
    }
	
	/**
     * @Route("/images/fetch")
     */
    public function fetchImagesAction()
    {
		
        $result = ImageDocument::fetchImages();
		return new Response(json_encode([ 'status' => 'success', 'SuccessMessage' => 'Images Fetched Successfully','data' => $result, 'Success_Token' => '101']));

		
    }
	
	
}