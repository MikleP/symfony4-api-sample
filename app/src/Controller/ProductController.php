<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\VATClass;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ProductController
 * @package App\Controller
 * @Route("/product")
 */
class ProductController extends AbstractController
{
	/**
	 * @Route("", name="product.add")
	 * @Method("PUT")
	 * @param Request $request
	 * @param SerializerInterface $serializer
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
    public function add(Request $request, SerializerInterface $serializer)
    {
    	$json = $request->getContent();
    	/** @var Product $product */
    	$product = $serializer->deserialize($json, Product::class, 'json');
		$entityManager = $this->getDoctrine()->getManager();

		$vatRepo = $this->getDoctrine()->getRepository(VATClass::class);
		$vatClass = $vatRepo->find($product->getVatId());
		$product->setVat($vatClass);

		$entityManager->persist($product);

		try {
			$entityManager->flush();
		} catch (Exception $e) {
			return $this->json([
				'success' => false,
				'message' => 'Failed to add a product.',
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json([
			'success' => true,
			'message' => 'Product was successfully added.',
        ], Response::HTTP_CREATED);
    }

	/**
	 * @Route("/list", name="product.list")
	 * @Method("GET")
	 */
	public function list()
	{
		$productRepo = $this->getDoctrine()->getRepository(Product::class);
		$products = $productRepo->findAll();

		return $this->json($products);
    }

	/**
	 * @Route("/barcode/{barcode}", name="product.get.barcode", requirements={"barcode"="\S+"})
	 * @Method("GET")
	 * @param $barcode
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function getByBarcode($barcode)
	{
		$productRepo = $this->getDoctrine()->getRepository(Product::class);
		$product = $productRepo->findOneBy(['barcode' => $barcode]);

		return $this->json($product);
	}
}
