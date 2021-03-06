<?php

namespace Kassner\FinancesBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TransactionType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array(
                'choices' => array(
                    'expense' => 'Expense',
                    'income' => 'Income',
                    'transfer' => 'Transfer'
                )
            ))
            ->add('amount')
            ->add('date')
            ->add('account', 'entity', array(
                'class' => 'Kassner\FinancesBundle\Entity\Account',
                'required' => true,
                'query_builder' => function(EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('e');
                    $qb->orderBy('e.name', 'ASC');
                    return $qb;
                }
            ))
            /**
             * @TODO disable/hide field via javascript when transaction is not a transfer
             */
            ->add('destination_account', 'entity', array(
                'class' => 'Kassner\FinancesBundle\Entity\Account',
                'required' => false,
                'property_path' => 'transfer.account',
                'query_builder' => function(EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('e');
                    $qb->orderBy('e.name', 'ASC');
                    return $qb;
                }
            ))
            ->add('payee', 'entity', array(
                'class' => 'Kassner\FinancesBundle\Entity\Payee',
                'required' => false,
                'query_builder' => function(EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('e');
                    $qb->orderBy('e.name', 'ASC');
                    return $qb;
                }
            ))
            ->add('category', 'entity', array(
                'class' => 'Kassner\FinancesBundle\Entity\Category',
                'required' => false,
                'query_builder' => function(EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('e');
                    $qb->orderBy('e.name', 'ASC');
                    return $qb;
                }
            ))
            ->add('isReconciled', 'checkbox', array(
                'required' => false
            ))
            ->add('description', 'textarea', array(
                'required' => false
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Kassner\FinancesBundle\Entity\Transaction'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'kassner_financesbundle_transaction';
    }

}
