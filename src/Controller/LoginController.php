<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    
    #[Route("/Login", name: "Login", methods: ['GET', 'POST'])]
    public function Login(Request $request): Response
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

                $Email = $_POST["Email"];
                $Password = $_POST["Password"];
    
                $sql = "SELECT * FROM `users` WHERE Email = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 's', $Email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
    
                if ($user = mysqli_fetch_assoc($result)) {
                    if (password_verify($Password, $user['Password'])) {
                        mysqli_close($conn);
                        return $this->redirect('data');
                    } else {
                        echo "Invalid credentials";
                    }
                } else {
                    echo "No user found with the given email.";
                }
    
                mysqli_close($conn);
            }


            return $this->render('Login.html.twig');
        
   }

   #[Route("/data", name: "data", methods: ['GET', 'POST'])]
   public function DataTable(Request $request): Response
   {
   
        $host = 'localhost';
        $db   = 'getion';
        $user = 'root';
        $pass = '';
    
        $conn = mysqli_connect($host, $user, $pass, $db);
    
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $sql = "SELECT `Id`, `Nom`, `Email` FROM `users`";
        $result = mysqli_query($conn, $sql);
        
        $users = [];
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
        }
        mysqli_close($conn);
        return $this->render('datatable.html.twig', ['users' => $users]);
           
       }
       
}

 

    

