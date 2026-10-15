<?php

// Copyright (C) 2010-2026, the Friendica project
// SPDX-FileCopyrightText: 2010-2026 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Content;

use Friendica\App\BaseURL;
use Friendica\Content\Contact\Repository\ContactByType;
use Friendica\Content\Text\HTML;
use Friendica\Core\Addon\AddonHelper;
use Friendica\Core\L10n;
use Friendica\Core\Renderer;
use Friendica\Core\Session\Capability\IHandleUserSessions;
use Friendica\Model\Contact;

/**
 * This class handles methods related to the group functionality
 */
class GroupManager
{
	private const CONTACT_TYPES = [Contact::TYPE_COMMUNITY];

	public function __construct(
		private readonly ContactByType $contacts,
		private readonly AddonHelper $addonHelper,
		private readonly BaseURL $baseUrl,
		private readonly L10n $l10n,
		private readonly IHandleUserSessions $session,
	) {}

	/**
	 * Function to list all groups a user is connected with
	 *
	 * @param int     $uid         of the profile owner
	 * @param boolean $lastitem    Sort by lastitem
	 * @param boolean $showhidden  Show groups which are not hidden
	 * @param boolean $showprivate Show private groups
	 *
	 * @return array
	 *    'url'    => group url
	 *    'name'    => group name
	 *    'id'    => number of the key from the array
	 *    'micro' => contact photo in format micro
	 *    'thumb' => contact photo in format thumb
	 * @throws \Exception
	 */
	public function getList(int $uid, bool $lastitem, bool $showhidden = true, bool $showprivate = false): array
	{
		return $this->contacts->selectForUser($uid, self::CONTACT_TYPES, $lastitem, $showhidden, $showprivate);
	}


	/**
	 * Group list widget
	 *
	 * Sidebar widget to show subscribed Friendica groups. If activated
	 * in the settings, it appears in the network page sidebar
	 *
	 * @param int $uid The ID of the User
	 * @return string
	 * @throws \Friendica\Network\HTTPException\InternalServerErrorException
	 * @throws \ImagickException
	 */
	public function widget(int $uid): string
	{
		//sort by last updated item
		$contacts      = $this->getList($uid, true, true, true);
		$total         = count($contacts);
		$visibleGroups = 10;

		$id = 0;

		$entries = [];

		foreach ($contacts as $contact) {
			$entry = [
				'url'          => 'contact/' . $contact['id'] . '/conversations',
				'external_url' => Contact::magicLinkByContact($contact),
				'name'         => $contact['name'],
				'cid'          => $contact['id'],
				'micro'        => $this->baseUrl->remove(Contact::getMicro($contact)),
				'id'           => ++$id,
			];
			$entries[] = $entry;
		}

		$tpl = Renderer::getMarkupTemplate('widget/group_list.tpl');

		return Renderer::replaceMacros(
			$tpl,
			[
				'$title'                         => $this->l10n->t('Groups'),
				'$groups'                        => $entries,
				'$link_desc'                     => $this->l10n->t('External link to group'),
				'$new_group_page'                => 'register/?type=group',
				'$total'                         => $total,
				'$visible_groups'                => $visibleGroups,
				'$showless'                      => $this->l10n->t('show less'),
				'$showmore'                      => $this->l10n->t('show more'),
				'$create_new_group'              => $this->l10n->t('Create new group'),
				'$addon_group_directory_enabled' => $this->addonHelper->isAddonEnabled('groupdirectory'),
				'$visit_groupdirectory'          => $this->l10n->t('Find groups to join'),
			],
		);
	}

	/**
	 * Format group list as contact block
	 *
	 * This function is used to show the group list in
	 * the advanced profile.
	 *
	 * @param int $uid The ID of the User
	 * @return string
	 * @throws \Friendica\Network\HTTPException\InternalServerErrorException
	 * @throws \ImagickException
	 */
	public function profileAdvanced(int $uid): string
	{
		if (!Feature::isEnabled($uid, Feature::GROUPS)) {
			return '';
		}

		$o = '';

		// placeholder in case somebody wants configurability
		$show_total = 9999;

		//don't sort by last updated item
		$lastitem = false;

		$contacts = $this->getList($uid, $lastitem, false, false);

		$total_shown = 0;
		foreach ($contacts as $contact) {
			$o .= HTML::micropro($contact, true, 'grouplist-profile-advanced');
			$total_shown++;
			if ($total_shown == $show_total) {
				break;
			}
		}

		return $o;
	}

	/**
	 * count unread group items
	 *
	 * Count unread items of connected groups and private groups
	 *
	 * @return array
	 *    'id' => contact id
	 *    'name' => contact/group name
	 *    'count' => counted unseen group items
	 * @throws \Exception
	 */
	public function countUnseenItems(): array
	{
		$uid = $this->session->getLocalUserId();
		if (!is_int($uid)) {
			return [];
		}

		return $this->contacts->countUnseenItems($uid, self::CONTACT_TYPES);
	}
}
