<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Usuario;

class UsuarioFixtures extends Fixture
{

     private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
       $this->passwordEncoder = $passwordEncoder;
     }


    public function load(ObjectManager $manager)
    {
        $user = new Usuario();
        $user->setUsername('gmunoz');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail('a@a.cl');
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$LkQ3SVdjVzhXMzlsbnoxMA$FotBJllJgfRgos3qmNv2HFi6n5wnd26ZXMhAjNSBVCU');
       //$user->setPassword($this->passwordEncoder->encodePassword(
      //     $user,
      //      '123qwe'
      //   ));
         $manager->persist($user);
        $manager->flush();
    }
}
