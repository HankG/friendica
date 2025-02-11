{{*
  * Copyright (C) 2010-2024, the Friendica project
  * SPDX-FileCopyrightText: 2010-2024 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}
<div id="mail-display-subject">
	<span class="{{if $thread_seen}}seen{{else}}unseen{{/if}}">{{$thread_subject}}</span>
	<a href="message/dropconv/{{$thread_id}}" onclick="return confirmDelete();"  title="{{$delete}}" class="mail-delete icon s22 delete"></a>
</div>

{{foreach $mails as $mail}}
	<div id="tread-wrapper-{{$mail_item.id}}" class="tread-wrapper">
		{{include file="mail_conv.tpl"}}
	</div>
{{/foreach}}

{{include file="prv_message.tpl"}}
