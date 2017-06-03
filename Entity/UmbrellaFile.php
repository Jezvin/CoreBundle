<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 21/05/17
 * Time: 22:08.
 */

namespace Umbrella\CoreBundle\Entity;

use Umbrella\CoreBundle\Component\Core\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class UmbrellaFile.
 *
 * @ORM\Entity
 * @ORM\Table(name="umbrella_file")
 */
class UmbrellaFile extends BaseEntity
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    public $name;

    /**
     * @var int
     * @ORM\Column(type="smallint", nullable=false)
     */
    public $size;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    public $md5;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    public $mimeType;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    public $fileName;
}
