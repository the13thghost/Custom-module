<?php

namespace Drupal\date_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block with dynamic output.
 *
 * @Block(
 *   id = "date_block",
 *   admin_label = @Translation("Date block"),
 *   category = @Translation("Date Block")
 * )
 */
class DateBlock extends BlockBase implements ContainerFactoryPluginInterface{
    
    protected $daysLeft;

    /**
     * Provides a DateBlock object.
     *
     * @param array $configuration
     * @param string $plugin_id
     * @param mixed $plugin_definition
     * @param \Drupal\date_block\DaysLeftCalc $daysLeft
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, $daysLeft) {
        parent::__construct($configuration, $plugin_id, $plugin_definition, $daysLeft);
        $this->daysLeft = $daysLeft;
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $configuration
     * @param string $plugin_id
     * @param mixed $plugin_definition
     *
     * @return static
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('date_block.date_service')
        );
    }

    /**
    * {@inheritdoc}
    */
    public function build() {
        // Get node via route
        $node = \Drupal::routeMatch()->getParameter('node');
        // Get event start date
        $datetime = $node->field_event_date->value;
        // Get node type
        $type = $node->bundle();

        if($datetime && $type == 'event') {
            $output = $this->daysLeft->getDaysLeft($datetime);
        } else {
            $output = "This block is not intended to be placed here.";
        }
            
        return [
            '#markup' => $output
        ];
    }

    // Disable caching
    public function getCacheMaxAge() {
        return 0;
    }
}
