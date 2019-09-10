<?php
	declare(strict_types=1);

	namespace App\Form\Vendor;

	use App\Entity\Vendor\Vendors;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\OptionsResolver\OptionsResolver;

	class VendorsAddType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options):void
		{
			$builder
				->add('security', VendorsSecurityType::class)
				->add('vendorEnGb', VendorsEnGbType::class)
				->add('summit', SubmitType::class, array(
					'attr' => array(
						'class' => 'btn'
					)
				))
			;

		}

		public function configureOptions(OptionsResolver $resolver):void
		{
			$resolver->setDefaults([
				'data_class' => Vendors::class,
			]);
		}
	}