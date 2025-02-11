{{*
  * Copyright (C) 2010-2024, the Friendica project
  * SPDX-FileCopyrightText: 2010-2024 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}
<div id="message-sidebar" class="widget">
	<div id="message-new" class="{{if $new.sel}}selected{{/if}}"><a href="{{$new.url}}" accesskey="m">{{$new.label}}</a> </div>

 {{if $tabs}}
	<div id="message-preview">
		{{$tabs nofilter}}
	</div>
  {{/if}}

</div>
