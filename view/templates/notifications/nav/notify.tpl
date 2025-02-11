{{*
  * Copyright (C) 2010-2024, the Friendica project
  * SPDX-FileCopyrightText: 2010-2024 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}
<li class="notification-{{if !$notify.seen}}un{{/if}}seen">
	<a href="{{$notify.href}}" title="{{$notify.localdate}}"><img data-src="{{$notify.contact.photo}}" height="24" width="24" alt="" loading="lazy"/>{{$notify.richtext nofilter}} <span class="notif-when">{{$notify.ago}}</span></a>
</li>
