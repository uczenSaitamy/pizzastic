<?php

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Form\AddressType;
use App\Entity\Address;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;

class AddressController extends BaseController
{
    protected $prefix = 'account/address';

    public function index()
    {
        $user = $this->getUser();
        $addresses = $user->getAddresses();

        return $this->view('index', ['addresses' => $addresses]);
    }

    public function create()
    {
        $address = new Address();
        $addressForm = $this->createForm(AddressType::class, $address)->createView();

        return $this->view('edit', ['addressForm' => $addressForm, 'address' => $address]);
    }

    public function store(Request $request)
    {
        $form = $this->createForm(AddressType::class, $address = new Address());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            try {
                $address->setUser($this->getUser());
                $em->persist($address);
                $em->flush();
                $em->getConnection()->commit();

                $this->addFlash('success', 'Your address has been saved.');
            } catch (Exception $e) {
                $em->getConnection()->rollBack();
                // TODO LOG EXCEPTION $e
                $this->addFlash('error', 'Your address has not been saved.');
            }
        }

        return $this->redirectToRoute('account.address');
    }

    public function edit(Address $address)
    {
        if (!$address->isUser($this->getUser())) {
            return $this->redirectToRoute('account.address');
        }

        $addressForm = $this->createForm(AddressType::class, $address)->createView();
        return $this->view('edit', ['addressForm' => $addressForm, 'address' => $address]);
    }

    public function update(Request $request, Address $address)
    {
        if (!$address->isUser($this->getUser())) {
            return $this->redirectToRoute('account.address');
        }

        $form = $this->createForm(AddressType::class, $address, ['method' => 'PATCH']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            try {
                $em->persist($address);
                $em->flush();
                $em->getConnection()->commit();

                $this->addFlash('success', 'Your address has been saved.');
            } catch (Exception $e) {
                $em->getConnection()->rollBack();
                // TODO LOG EXCEPTION $e
                $this->addFlash('error', 'Your address has not been saved.');
            }
        }

        return $this->redirectToRoute('account.address');
    }

    public function destroy(Address $address)
    {
        if (!$address->isUser($this->getUser())) {
            return $this->redirectToRoute('account.address');
        }

        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();

        try {
            $em->remove($address);
            $em->flush();
            $em->getConnection()->commit();

            $this->addFlash('success', 'Your address has been removed successfully.');
        } catch (Exception $e) {
            $em->getConnection()->rollBack();
            // TODO LOG EXCEPTION $e
            $this->addFlash('errors', 'Your address has not been saved.');
        }

        return $this->redirectToRoute('account.address');
    }
}
