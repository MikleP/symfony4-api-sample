<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductEntry;
use App\Entity\Receipt;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ReceiptController
 * @package App\Controller
 * @Route("/receipt")
 */
class ReceiptController extends AbstractController
{
    /**
     * @Route("/add", name="receipt")
     */
    public function index()
    {
    	$productRepo = $this->getDoctrine()->getRepository(Product::class);
    	$product = $productRepo->find(1);
    	$productEntry = new ProductEntry();
		$productEntry->setProduct($product)
			->setCount(2);

    	$receipt = new Receipt();
    	$receipt->addProductEntry($productEntry)
			->setUserId(1)
			->setCreatedAt(new \DateTime('now'))
			->setModifiedAt(new \DateTime('now'))
			->setStatus(Receipt::STATUS_ACTIVE);

		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($receipt);
		$entityManager->flush();

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ReceiptController.php',
        ]);
    }

	/**
	 * @Route("/current", name="receipt.current")
	 * @Method("GET")
	 */
	public function current()
	{
		$receiptRepo = $this->getDoctrine()->getRepository(Receipt::class);
		$receipt = $receiptRepo->findOneBy(['status' => Receipt::STATUS_ACTIVE]);

		//TODO: Fix DateTime serialization
		return $this->json($receipt);
    }

	/**
	 * @Route("/add/product/{barcode}", name="receipt.add.product.barcode", requirements={"barcode"="\S+"})
	 * @Method("PUT")
	 * @param $barcode
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function addProduct($barcode)
	{
		$receiptRepo = $this->getDoctrine()->getRepository(Receipt::class);
		$receipt = $receiptRepo->findOneBy(['status' => Receipt::STATUS_ACTIVE]);

		if (!$receipt) {
			$receipt = new Receipt();
			$receipt->setUserId(1);
		}

		$productRepo = $this->getDoctrine()->getRepository(Product::class);
		$product = $productRepo->findOneBy(['barcode' => $barcode]);

		$productEntry = new ProductEntry();
		$productEntry->setProduct($product)
			->setCount(1);

		$receipt->addProductEntry($productEntry);

		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($receipt);
		$entityManager->flush();

		try {
			$entityManager->flush();
		} catch (Exception $e) {
			return $this->json([
				'success' => false,
				'message' => 'Failed to update Receipt.',
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json([
			'success' => true,
			'message' => 'Receipt was successfully updated.',
		], Response::HTTP_CREATED);
	}

	/**
	 * @Route("/entry/amount", name="receipt.add.product.barcode")
	 * @Method("PUT")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 * @internal param $amount
	 */
	public function setProductEntryAmount(Request $request)
	{
		try {
			$body = json_decode($request->getContent());
			$amount = $body->amount;
		} catch (Exception $exception) {
			return $this->json([
				'success' => false,
				'message' => 'Failed to update Product entry.',
			], Response::HTTP_BAD_REQUEST);
		}
		$em = $this->getDoctrine()->getManager();
		$receiptRepo = $em->getRepository(Receipt::class);
		$receipt = $receiptRepo->findOneBy(['status' => Receipt::STATUS_ACTIVE]);

		if (!$receipt) {
			return $this->json([
				'success' => false,
				'message' => 'Failed to update Product entry. Receipt not found. ',
			], Response::HTTP_NOT_FOUND);
		}

		$productEntries = $receipt->getProductEntries();
		/** @var ProductEntry $lastEntry */
		$lastEntry = array_pop($productEntries);
		$lastEntry->setCount($amount);
		array_push($productEntries, $lastEntry);
		$receipt->setProductEntries($productEntries);

		try {
			//TODO: Check flush. Object is not updated.
			$em->flush();
		} catch (Exception $e) {
			return $this->json([
				'success' => false,
				'message' => 'Failed to update Receipt.',
			], Response::HTTP_BAD_REQUEST);
		}

		return $this->json([
			'success' => true,
			'message' => 'Receipt was successfully updated.',
		], Response::HTTP_CREATED);
	}
}
