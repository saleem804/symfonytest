<?php 
// src/AppBundle/Entity/ImageDocument.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="images")
 */
class ImageDocument
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $label;
	
	/**
     * @ORM\Column(type="string", length=100)
     */
    protected $file_name;
	

   

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return ImageDocument
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return ImageDocument
     */
    public function setFileName($fileName)
    {
        $this->file_name = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }
	public function fetchImages(){
		$repository = $this->getDoctrine()
			->getRepository('AppBundle:ImageDocument');
		$image_dataset = $repository->findAll();
		$result = array();
		foreach($image_dataset as $image_data){
			$resultobj = new \stdClass();
			$resultobj->id = $image_data->getId();
			$resultobj->label = $image_data->getLabel();
			$resultobj->FileName = $image_data->getFileName();
			$result[] = $resultobj;
			
		}
		return $result;
	}
	public function SaveFile(){
		$basename = basename($_FILES['file']['name']);
		$file = new UploadedFile($_FILES['file']['tmp_name'],$basename);
		$path_info = pathinfo($basename);
		$extension = $path_info['extension'];
		$fileName = uniqid() .'.'. $extension;
		$file->move('upload/images', $fileName);
		return $fileName;
	}
	
}
