<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Form\DataTransferObject\ProductDTO;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\ImportTool\FileDataValidator;
use App\Service\Processor\ProductCreator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @var ProductCreator
     */
    private $creator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ProductController constructor.
     * @param ProductCreator $creator
     * @param EntityManagerInterface $em
     */
    public function __construct(ProductCreator $creator, EntityManagerInterface $em)
    {
        $this->creator = $creator;
        $this->em = $em;
    }

    /**
     * @Route("/", name="products", methods={"GET"})
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request): Response
    {
        $productDTO = new ProductDTO($this->creator);
        $form = $this->createForm(ProductType::class, $productDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (true === $productDTO->createProduct()){
                $this->em->flush();
            }

            return $this->redirectToRoute('products');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     * @param Product $product
     * @return Response
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     * @param Request $request
     * @param ProductDTO $productDTO
     * @return Response
     */
    public function edit(Request $request, ProductDTO $productDTO): Response
    {
        $form = $this->createForm(ProductType::class, $productDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('products');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $productDTO,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('products');
    }
}
