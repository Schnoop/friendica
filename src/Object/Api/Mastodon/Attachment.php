<?php
/**
 * @copyright Copyright (C) 2010-2022, the Friendica project
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

namespace Friendica\Object\Api\Mastodon;

use Friendica\BaseDataTransferObject;

/**
 * Class Attachment
 *
 * @see https://docs.joinmastodon.org/entities/attachment
 */
class Attachment extends BaseDataTransferObject
{
	/** @var string */
	protected $id;
	/** @var string */
	protected $type;
	/** @var string */
	protected $url;
	/** @var string */
	protected $preview_url;
	/** @var string */
	protected $remote_url;
	/** @var string */
	protected $text_url;
	/** @var string */
	protected $description;
	/** @var array */
	protected $meta;

	/**
	 * Creates an attachment
	 *
	 * @param array $attachment
	 * @throws \Friendica\Network\HTTPException\InternalServerErrorException
	 */
	public function __construct(array $attachment, string $type, string $url, string $preview, string $remote)
	{
		$this->id = (string)$attachment['id'];
		$this->type = $type;
		$this->url = $url;
		$this->preview_url = $preview;
		$this->remote_url = $remote;
		$this->text_url = $this->remote_url ?? $this->url;
		$this->description = $attachment['description'];
		if ($type === 'image') {
			if ((int) $attachment['width'] > 0 && (int) $attachment['height'] > 0) {
				$this->meta['original']['width'] = (int) $attachment['width'];
				$this->meta['original']['height'] = (int) $attachment['height'];
				$this->meta['original']['size'] = (int) $attachment['width'] . 'x' . (int) $attachment['height'];
				$this->meta['original']['aspect'] = (float) ((int)  $attachment['width'] / (int) $attachment['height']);
			}

			if ((int) $attachment['preview-width'] > 0 && (int) $attachment['preview-height'] > 0) {
				$this->meta['small']['width'] = (int) $attachment['preview-width'];
				$this->meta['small']['height'] = (int) $attachment['preview-height'];
				$this->meta['small']['size'] = (int) $attachment['preview-width'] . 'x' . (int) $attachment['preview-height'];
				$this->meta['small']['aspect'] = (float) ((int)  $attachment['preview-width'] / (int) $attachment['preview-height']);
			}
		}
	}

	/**
	 * Returns the current entity as an array
	 *
	 * @return array
	 */
	public function toArray(): array
	{
		$attachment = parent::toArray();

		if (empty($attachment['remote_url'])) {
			$attachment['remote_url'] = null;
		}

		return $attachment;
	}
}
