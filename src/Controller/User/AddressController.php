<?php

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Form\AddressType;
use App\Entity\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddressController extends BaseController
{
    protected $prefix = 'account/address';

    public function index()
    {
        if ($user = $this->getUser()) {
            $addresses = $user->getAddresses();

            // foreach ($addresses as $address) {
            //     $addressesForms[$address->getId()] = $this->createForm(AddressType::class, $address, ['method' => 'PATCH'])->createView();
            // }
            // $newAddressForm = $this->createForm(AddressType::class, new Address())->createView();
        }
        return $this->view('index', ['addresses' => $addresses]);
        // return $this->view('index', ['addressesForms' => $addressesForms, 'user' => $user, 'newAddressForm' => $newAddressForm]);
    }

    public function store(Request $request, ValidatorInterface $valid)
    {
        // dd();
        if ($user = $this->getUser()) {
            $form = $this->createForm(AddressType::class, $address = new Address());
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // dd($valid->validate($address));
                $em = $this->getDoctrine()->getManager();
                $address->setUser($user);
                $em->persist($address);
                $em->flush();

                $this->addFlash('success', 'Your data has been saved.');
            }
        }
        return $this->redirectToRoute('account.address');
        // return $this->view('index', ['form' => $form->createView(), 'user' => $user]);
    }

    public function update(Request $request, Address $address)
    {
        if ($this->getUser()) {
            $form = $this->createForm(AddressType::class, $address, ['method' => 'PATCH']);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($address);
                $em->flush();

                $this->addFlash('success', 'Your data has been saved.');
            }
        }
        return $this->redirectToRoute('account.address');
    }
}
