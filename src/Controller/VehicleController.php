<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\VehicleType;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("account/vehicle")
 */
class VehicleController extends AbstractController
{
    /**
     * @Route("/", name="vehicle_index", methods={"GET"})
     */
    public function index(VehicleRepository $vehicleRepository): Response
    {
        return $this->render('account/vehicle/index.html.twig', [
            'vehicles' => $vehicleRepository->findby(['user' => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/new", name="vehicle_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $vehicle = new Vehicle();
        $vehicle->setUser($this->getUser());
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vehicle);
            $entityManager->flush();

            return $this->redirectToRoute('vehicle_index');
        }

        return $this->render('account/vehicle/new.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vehicle_show", methods={"GET"})
     */
    public function show(Vehicle $vehicle): Response
    {
        if ($vehicle->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('account/vehicle/show.html.twig', [
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vehicle_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vehicle $vehicle): Response
    {
        if ($vehicle->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vehicle_index', [
                'id' => $vehicle->getId(),
            ]);
        }

        return $this->render('account/vehicle/edit.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vehicle_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Vehicle $vehicle): Response
    {
        if ($vehicle->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        if ($this->isCsrfTokenValid('delete'.$vehicle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vehicle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vehicle_index');
    }
}
