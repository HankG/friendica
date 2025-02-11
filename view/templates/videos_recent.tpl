{{*
  * Copyright (C) 2010-2024, the Friendica project
  * SPDX-FileCopyrightText: 2010-2024 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}
<h3>{{$title}}</h3>
{{if $can_post}}
{{*<a id="video-top-upload-link" href="{{$upload.1}}">{{$upload.0}}</a>*}}
{{/if}}

<div class="videos">
{{foreach $videos as $video}}
	{{include file="video_top.tpl"}}
{{/foreach}}
</div>
<div class="videos-end"></div>
