{{*
  * Copyright (C) 2010-2024, the Friendica project
  * SPDX-FileCopyrightText: 2010-2024 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}

<div class="notif-item {{if !$item_seen}}unseen{{/if}}" {{if $item_seen}}aria-hidden="true"{{/if}}>
	<a href="{{$notification.link}}">
		<img src="{{$notification.image}}" aria-hidden="true" class="notif-image">
		<link rel="stylesheet" href="view/global.css">
		<span class="notif-text">{{$notification.text}}</span>
		<span class="notif-when">{{$notification.ago}}</span>
	</a>
</div>
