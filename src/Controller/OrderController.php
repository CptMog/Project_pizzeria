<?php
namespace App\Controller;

use App\Entity\ProductOrder;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Date;


class OrderController extends AbstractController
{
    public function addOrder(Request $request): Response
    {
        $session = $request->getSession();

        $tabId = $session->get('cart');
        $qte = $session->get('cart_qte');
        $tot = 0;
        if(!empty($tabId)){
            //calcul du total HT
            for($i = 0; $i < count($tabId); $i++){
                $product = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($tabId[$i]);

                $tot += $product->getPrice() * $qte[$i];
            }
            ///////////////////

            $order = new Order();

            $order->setUser($this->getUser());
            $order->setDate(new \DateTime());
            $order->setTva(0.2); //20%
            $order->setPrice($tot);
            $order->setDelivery(3.0);
            $order->setDiscount(0.0);
            $order->setTotal(($tot + ($tot*$order->getTva()) + $order->getDelivery())-$order->getDiscount());
            
            
            $bddManager = $this->getDoctrine()->getManager();
            $bddManager->persist($order);
            $bddManager->flush();

            for($i = 0; $i < count($tabId); $i++){
                $product = $this->getDoctrine()
                ->getRepository(Product::class)
                ->find($tabId[$i]);

                $ligne = new ProductOrder();
                
                $ligne->setIdOrder($order);
                $ligne->setIdProduct($product);
                $ligne->setQuantity($qte[$i]);

                $bddManager = $this->getDoctrine()->getManager();
                $bddManager->persist($ligne);
                $bddManager->flush();
            }

            return $this->redirectToRoute('order');
        }else{
            return $this->redirectToRoute('cart');
        }
    }

    public function display(Request $request): Response
    {
        $session = $request->getSession();
        $tabId = $session->get('cart');
        $qte = $session->get('cart_qte');

        if(!empty($tabId)){
            $itemBasket=[];
            if(!empty($tabId)){
                for($i = 0; $i < count($tabId); $i++){
                    $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->find($tabId[$i]);
                    $itemBasket[] = $product->setQuantity($qte[$i]);
                }
            }

            $order = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findOneBy([
                'user' => $this->getUser(),
                'date' => new \DateTime(),
            ]);

            // dd($order);

            return $this->render('orders.html.twig',[
                'products' => $itemBasket,
                'id'    => $order->getId(),
                'orders' =>  $order
            ]);
        }else{
            return $this->redirectToRoute('cart');
        }
    }
}
