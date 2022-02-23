<?php
namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    public function add(Request $request,  SluggerInterface $slugger): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('photo')->getData();
            $product = $form->getData();
            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'.'.$photoFile->guessExtension();
                
                try {
                    $photoFile->move(
                        $this->getParameter('img'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo "Error : cannot move the image to 'img' !";
                }

                 $product->setPhoto($newFilename);
            }

            $bddManager = $this->getDoctrine()->getManager();
            $bddManager->persist($product);
            $bddManager->flush();

        }

            return $this->render('addProduct.html.twig', [
                'form' => $form->createView(),
            ]);
    }


    public function all(Request $request): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        return $this->render('allProduct.html.twig', [
            "products" => $products
        ]);
    }

}