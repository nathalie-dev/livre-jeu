<?php

namespace App\Test\Controller;

use App\Entity\Etape;
use App\Repository\EtapeRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EtapeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EtapeRepository $repository;
    private string $path = '/etape/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Etape::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Etape index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'etape[texte_ambiance]' => 'Testing',
            'etape[libelle]' => 'Testing',
            'etape[aventureDebutee]' => 'Testing',
            'etape[aventure]' => 'Testing',
            'etape[finAventure]' => 'Testing',
        ]);

        self::assertResponseRedirects('/etape/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etape();
        $fixture->setTexte_ambiance('My Title');
        $fixture->setLibelle('My Title');
        $fixture->setAventureDebutee('My Title');
        $fixture->setAventure('My Title');
        $fixture->setFinAventure('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Etape');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Etape();
        $fixture->setTexte_ambiance('My Title');
        $fixture->setLibelle('My Title');
        $fixture->setAventureDebutee('My Title');
        $fixture->setAventure('My Title');
        $fixture->setFinAventure('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'etape[texte_ambiance]' => 'Something New',
            'etape[libelle]' => 'Something New',
            'etape[aventureDebutee]' => 'Something New',
            'etape[aventure]' => 'Something New',
            'etape[finAventure]' => 'Something New',
        ]);

        self::assertResponseRedirects('/etape/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTexte_ambiance());
        self::assertSame('Something New', $fixture[0]->getLibelle());
        self::assertSame('Something New', $fixture[0]->getAventureDebutee());
        self::assertSame('Something New', $fixture[0]->getAventure());
        self::assertSame('Something New', $fixture[0]->getFinAventure());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Etape();
        $fixture->setTexte_ambiance('My Title');
        $fixture->setLibelle('My Title');
        $fixture->setAventureDebutee('My Title');
        $fixture->setAventure('My Title');
        $fixture->setFinAventure('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/etape/');
    }
}
