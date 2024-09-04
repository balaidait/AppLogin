<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request; 

class ActionController extends AbstractController
{ 
    
    #[Route("/delete/{id}", name: "delete", methods: ['GET'])]
    public function delete($id): Response
    {
        
        $host = 'localhost';
        $db   = 'getion';
        $user = 'root';
        $pass = '';

        $conn = mysqli_connect($host, $user, $pass, $db);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $sql = "DELETE FROM `users` WHERE Id = $id";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            mysqli_close($conn);
            return $this->redirectToRoute('data');  
        } else {
            $error = "Error: " . mysqli_error($conn);
            mysqli_close($conn);
        }
    }

 #[Route("/Update/{id}", name: "Update", methods: ['GET', 'POST'])]
 public function update(Request $request, int $id): Response
 {
    
        $host = 'localhost';
        $db = 'getion';
        $user = 'root';
        $pass = '';
        
        $conn = mysqli_connect($host, $user, $pass, $db);
        
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        if ($request->isMethod('POST')) {
            $Nom = $request->request->get('Nom');
            $Email = $request->request->get('Email');
            $password = $request->request->get('Password');
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = "UPDATE users SET Nom = '$Nom', Email = '$Email', Password = '$hashedPassword' WHERE Id = $id";
        if (mysqli_query($conn, $sql)) {
            echo "Record updated successfully!";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }

        return $this->redirectToRoute('data');
    } else {
        $sql = "SELECT * FROM users WHERE Id = $id";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result); 
        } else {
            echo "Error fetching data: " . mysqli_error($conn);
        }

        mysqli_close($conn);
        return $this->render('Update.html.twig', ['row' => $row]);
    }
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
