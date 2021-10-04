<?php
declare(strict_types=1);

namespace App\Entity\Vendor;

use App\Entity\AttachmentTrait;
use App\Entity\BaseTrait;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="vendors_attachments", indexes={
 * @ORM\Index(name="vendor_attachment_idx", columns={"slug"})}))
 * @ORM\Entity(repositoryClass="App\Repository\Vendor\VendorRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class VendorDocument
{
	use BaseTrait;
	use AttachmentTrait;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Vendor\Vendor", inversedBy="vendorDocumentAttachments")
	 * @ORM\JoinColumn(name="attachments_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
	 */
	private mixed $attachments;

	/**
	 * @return mixed
	 */
	public function getAttachments(): mixed
    {
		return $this->attachments;
	}

	/**
	 * @param Vendor $attachments
	 */
	public function setAttachment(Vendor $attachments): void
	{
		$this->attachments = $attachments;
	}
}