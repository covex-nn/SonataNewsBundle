<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\NewsBundle\Action;

use Sonata\ClassificationBundle\Model\CollectionManagerInterface;
use Sonata\NewsBundle\Model\BlogInterface;
use Sonata\NewsBundle\Model\PostManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CollectionPostArchiveAction extends AbstractPostArchiveAction
{
    /**
     * @var CollectionManagerInterface
     */
    private $collectionManager;

    public function __construct(
        BlogInterface $blog,
        PostManagerInterface $postManager,
        CollectionManagerInterface $collectionManager
    ) {
        parent::__construct($blog, $postManager);

        $this->collectionManager = $collectionManager;
    }

    /**
     * @param string $tag
     *
     * @return Response
     */
    public function __invoke(Request $request, $tag)
    {
        $collection = $this->collectionManager->findOneBy([
            'slug' => $tag,
            'enabled' => true,
        ]);

        if (!$collection || !$collection->getEnabled()) {
            throw new NotFoundHttpException('Unable to find the collection');
        }

        return $this->renderArchive($request, ['collection' => $collection], ['collection' => $collection]);
    }
}
