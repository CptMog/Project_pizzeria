<?php
namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    public function addToCart(int $idProduct): Response
    {    
        $session = new Session();
        $session->start();
        
        if(!empty($session->get('cart'))){
            //else
            $correspodance = 0;
            $qte = $session->get('cart_qte');
            $tabId = $session->get('cart');

            for($i=0; $i<count($tabId); $i++){
                if($tabId[$i] == $idProduct){
                    $qte[$i] += 1;
                    $correspodance=1;
                }
            }

            if($correspodance == 0){
                $tabId[] = $idProduct;
                $qte[] = 1;
            }

            $session->set('cart',$tabId);
            $session->set('cart_qte',$qte);

        }else{
            //first time
            $tabId[] = $idProduct;
            $qte[] = 1;
            $session->set('cart',$tabId);
            $session->set('cart_qte',$qte);
        }

        $qte_tot = 0;

        // dd($qte);
        for($i=0; $i<count($qte); $i++){
            $qte_tot += $qte[$i];
        }

        echo $qte_tot;
        exit();    
    }

    public function cart(): Response
    {
        $session = new Session();
        $session->start();
        $tabId = $session->get('cart');
        $qte = $session->get('cart_qte');

        $itemBasket=[];
        $tot = 0;
        if(!empty($tabId)){
            for($i = 0; $i < count($tabId); $i++){
                $product = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($tabId[$i]);
                $itemBasket[] = $product->setQuantity($qte[$i]);
                $tot += $product->getPrice() * $product->getQuantity();

            }
        }

        return $this->render('cart.html.twig',[
            'elements' =>$itemBasket,
            'tot'   => $tot
        ]);
    }

    public function removeToCart(int $idProduct): Response
    {
        $session = new Session();
        $session->start();
        $tabId = $session->get('cart');
        $qte = $session->get('cart_qte');
        $newTab = [];
        $newQte = [];
        $qte_ss = 0;

        for($i=0;$i<count($tabId); $i++){
            if($tabId[$i] != $idProduct ){
                $newTab[] = $tabId[$i];
                $newQte[] = $qte[$i];
            }else{
                $qte_ss = $qte[$i];
            }
        }

        $session->set('cart',$newTab);
        $session->set('cart_qte',$newQte);

        echo $qte_ss;
        exit();
    }
}
