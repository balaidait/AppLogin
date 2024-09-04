<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InscrireController extends AbstractController
{
    
    #[Route("/", name: "Inscrire", methods: ['GET', 'POST'])]
    public function Inscrire(Request $request): Response
    {
        $host = 'localhost';
        $db   = 'getion'; 
        $user = 'root';
        $pass = '';

        $conn = mysqli_connect($host, $user, $pass, $db);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        if ($request->isMethod('POST')) {
           
            $nom = $_POST['Nom'];
            $email = $_POST['Email'];
            $password = $_POST['Password'];

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            
            $sql = "INSERT INTO users (Nom, Email, Password) VALUES ('$nom', '$email', '$hashedPassword')";

            if (mysqli_query($conn, $sql)) {
               
                return $this->redirectToRoute('Login');
            } else {
                echo "Error: " . mysqli_error($conn);
            }
            mysqli_close($conn);
        }

        return $this->render('Inscrire.html.twig');
    }      
        
   
}
