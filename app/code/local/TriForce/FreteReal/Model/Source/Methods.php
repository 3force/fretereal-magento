<?php
class TriForce_FreteReal_Model_Source_Methods {    
	public function toOptionArray() {
		return array(
			array(
				'label' => "Sem Contrato",
				'value' => array(
					array('value' => '40010', 'label' => 'Sedex'),
					array('value' => '40045', 'label' => 'Sedex a cobrar'),
					array('value' => '40215', 'label' => 'Sedex 10'),
					array('value' => '40290', 'label' => 'Sedex hoje'),
					array('value' => '41106', 'label' => 'Pac')
				)
			),
			array(
				'label' => "Com Contrato",
				'value' => array(
					array('value' => '40096', 'label' => 'Sedex'),
					array('value' => '40126', 'label' => 'Sedex a cobrar'),
					array('value' => '41068', 'label' => 'Pac'),
					array('value' => '81019', 'label' => 'e-Sedex'),
					array('value' => '81027', 'label' => 'e-Sedex prioritário'),
					array('value' => '81035', 'label' => 'e-Sedex express'),
				)
			)
		);
	}
}