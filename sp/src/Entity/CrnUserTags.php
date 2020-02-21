<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrnUserTags
 *
 * @ORM\Table(name="crn_user_tags", uniqueConstraints={@ORM\UniqueConstraint(name="user_tag_unique", columns={"tag_id", "ausers_id"})}, indexes={@ORM\Index(name="ausers_id", columns={"ausers_id"})})
 * @ORM\Entity
 */
class CrnUserTags
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"comment"="Таблица хранит тэги пользователей для поиска"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="ausers_id", type="integer", nullable=false, options={"comment"="ausers.id"})
     */
    private $ausersId;

    /**
     * @var int
     *
     * @ORM\Column(name="tag_id", type="integer", nullable=false)
     */
    private $tagId;


}
