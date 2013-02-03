<?php
namespace Yjv\Bundle\ReportRenderingBundle\Tests\Renderer\Grid\Column\Type;

use Yjv\Bundle\ReportRenderingBundle\DataTransformer\PropertyPathTransformer;

use Yjv\Bundle\ReportRenderingBundle\Renderer\Grid\Column\Type\PropertyPathType;

use Yjv\Bundle\ReportRenderingBundle\Renderer\Grid\Column\Column;

class PropertyPathTypeTest extends TypeTestCase{

	protected $type;
	
	protected function setUp() {

		parent::setUp();
		$this->dataTransformerRegistry->set('property_path', new PropertyPathTransformer());
		$this->type = new PropertyPathType();
	}
	
	public function testGetName() {
		
		$this->assertEquals('property_path', $this->type->getName());
	}
	
	public function testBuildColumn(){
		
		$options = array('path' => 'column', 'required' => true, 'empty_value' => '', 'ignored_option' => 'ignored');
		$this->type->buildColumn($this->builder, $options);
		$column = $this->builder->getColumn();
		$dataTransformers = $column->getDataTransformers();
		$this->assertCount(1, $dataTransformers);
		$transformer = $dataTransformers[0];
		$this->assertInstanceOf('Yjv\Bundle\ReportRenderingBundle\DataTransformer\PropertyPathTransformer', $transformer);
		$this->assertEquals('column', $transformer->getOption('path'));
		$this->assertEquals(true, $transformer->getOption('required'));
		$this->assertEquals('', $transformer->getOption('empty_value'));
	}
	
	public function testSetDefaultOptions() {
		
		$optionsResolver = $this->getMockBuilder('Symfony\Component\OptionsResolver\OptionsResolverInterface')->getMock();
		$optionsResolver
			->expects($this->once())
			->method('setDefaults')
			->with(array(
				'path' => null,
				'required' => true,
				'empty_value' => ''
			))
			->will($this->returnValue($optionsResolver))
		;
		$optionsResolver
			->expects($this->once())
			->method('setAllowedTypes')
			->with(array(
				'path' => 'string',
				'required' => 'bool',
				'empty_value' => 'string'
			))
			->will($this->returnValue($optionsResolver))
		;
		
		$this->type->setDefaultOptions($optionsResolver);
	}
}