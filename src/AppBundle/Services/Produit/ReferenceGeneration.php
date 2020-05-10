<?php

namespace AppBundle\Services\Produit;

use Doctrine\ORM\EntityManager;
use ReverseRegex\Lexer;
use ReverseRegex\Parser;
use ReverseRegex\Generator\Scope;
use ReverseRegex\Random\MersenneRandom;
use ReverseRegex\Exception;

class ReferenceGeneration {

    /**
     *
     * @var EntityManager
     */
    private $em;

    /**
     *
     * @var array
     */
    private $references;

    /**
     * Constructor
     *
     * @author 	Abdelhak OUADDI <abdelhak.ouaddi@gmail.com>
     * @param 	EntityManager $em
     */
    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->loadExistReferences();
    }

    private function loadExistReferences() {
        $references = $this->em->createQueryBuilder()
                        ->select('p.reference')
                        ->from('AppBundle:Produit', 'p')
                        ->getQuery()->getScalarResult()
        ;
        $this->references = array_column($references, "reference");
    }

    private function isAvailable($reference) {
        return !in_array($reference, $this->references);
    }

    /**
     * Générer une référence valide et disponible
     *  @author 	Abdelhak OUADDI <abdelhak.ouaddi@gmail.com>
     * @return string
     */
    public function generateReference() {
        $regex = '^([A-Z]|[a-z]|[0-9]){10}$';
        $lexer = new Lexer($regex);
        $parser = new Parser($lexer, new Scope(), new Scope());
        $generator = $parser->parse()->getResult();
        $rand = rand(10000, 99999);
        $random = new MersenneRandom($rand);
        $index = 0;
        do {
            $index++;
            $reference = '';
            $generator->generate($reference, $random);
            if ($index === 100) {
                $index = 0;
                $rand = rand(10000, 99999);
                $random = new MersenneRandom($rand);
            }
        } while (!$this->isAvailable($reference));
        return $reference;
    }

}
