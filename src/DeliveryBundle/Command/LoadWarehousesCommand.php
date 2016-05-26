<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 24.02.16
 * Time: 23:21
 */

namespace DeliveryBundle\Command;

use DeliveryBundle\Entity\City;
use DeliveryBundle\Entity\Warehouse;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadWarehousesCommand extends ContainerAwareCommand
{
    /**
     * Configure
     */
    public function configure()
    {
        $this->setName('novaposhta:warehouses:load');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $content = file_get_contents('https://novaposhta.ua/timetable/GetJsonWarehouseList');
        $data = json_decode($content, true);

        $cities = [];
        $i = 0;

//        print_r($data['warehouses'][4]); exit;

        $types = [];
        $notAdded = [];

        foreach ($data['warehouses'] as $item) {
            $id = $item['cityRef'];

            if (isset($cities[$id])) {
                $city = $cities[$id];
            } else {
                $city = (new City())
                    ->setName($item['city_ru']);
                $manager->persist($city);
                $cities[$id] = $city;
            }

            if (is_string($item['address_ru']) && $item['address_ru']) {

                $typesMap = [
                    'cargo' => Warehouse::TYPE_CARGO,
                    'post' => Warehouse::TYPE_POST,
                    'mini' => Warehouse::TYPE_MINI,
                    'postomat' => Warehouse::TYPE_POSTOMAT
                ];

                $parts = explode(':', $item['address_ru']);
                if (count($parts) == 2) {
                    $address = trim($parts[1]);
                } else {
                    echo $item['address_ru'] . PHP_EOL;
                    $address = $item['address_ru'];
                }

                $warehouse = (new Warehouse())
                    ->setNumber($item['id'])
                    ->setAddress($address)
                    ->setCity($city)
                    ->setMaxWeight($item['maxWeightAllowed'])
                    ->setPhone($item['phone'])
                    ->setType($typesMap[$item['type']]);

                $manager->persist($warehouse);
            } else {
                $notAdded[] = $item;
            }

            if (!in_array($item['type'], $types)) {
                $types[] = $item['type'];
            }

            if ($i % 50 == 0) {
                $manager->flush();
            }

            $i++;
        }

        $manager->flush();

        $output->writeln(sprintf('Load completed. Loaded %s warehouses', $i));
        $output->writeln(sprintf('Not loaded: %s', print_r($notAdded, true)));
        $output->writeln(sprintf('Types: %s', print_r($types, true)));
    }
}