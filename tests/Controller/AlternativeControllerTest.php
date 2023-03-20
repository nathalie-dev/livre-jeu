<?php

namespace App\Test\Controller;

use App\Entity\Alternative;
use App\Repository\AlternativeRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlternativeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AlternativeRepository $repository;
    private string $path = '/alternative/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Alternative::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Alternative index');

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
            'alternative[texte_ambiance]' => 'Testing',
            'alternative[libelle]' => 'Testing',
            'alternative[etapePrecedente]' => 'Testing',
            'alternative[etapeSuivante]' => 'Testing',
        ]);

        self::assertResponseRedirects('/alternative/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Alternative();
        $fixture->setTexte_ambiance('My Title');
        $fixture->setLibelle('My Title');
        $fixture->setEtapePrecedente('My Title');
        $fixture->setEtapeSuivante('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Alternative');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Alternative();
        $fixture->setTexte_ambiance('My Title');
        $fixture->setLibelle('My Title');
        $fixture->setEtapePrecedente('My Title');
        $fixture->setEtapeSuivante('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'alternative[texte_ambiance]' => 'Something New',
            'alternative[libelle]' => 'Something New',
            'alternative[etapePrecedente]' => 'Something New',
            'alternative[etapeSuivante]' => 'Something New',
        ]);

        self::assertResponseRedirects('/alternative/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTexte_ambiance());
        self::assertSame('Something New', $fixture[0]->getLibelle());
        self::assertSame('Something New', $fixture[0]->getEtapePrecedente());
        self::assertSame('Something New', $fixture[0]->getEtapeSuivante());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Alternative();
        $fixture->setTexte_ambiance('My Title');
        $fixture->setLibelle('My Title');
        $fixture->setEtapePrecedente('My Title');
        $fixture->setEtapeSuivante('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/alternative/');
    }
}
