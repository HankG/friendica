{{*
  * Copyright (C) 2010-2026, the Friendica project
  * SPDX-FileCopyrightText: 2010-2026 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}
{{$live_update nofilter}}
{{foreach $threads as $thread}}
<div id="tread-wrapper-{{$thread.id}}" class="tread-wrapper panel toplevel_item">
    {{foreach $thread.items as $item}}
        {{if $item.comment_firstcollapsed}}
			<div class="hide-comments-outer">
				<span id="hide-comments-total-{{$thread.id}}" class="hide-comments-total">{{$thread.num_comments}}</span>
				<span id="hide-comments-{{$thread.id}}" class="hide-comments fakelink" onclick="showHideComments({{$thread.id}});">{{$thread.hide_text}}</span>
			</div>
			<div id="collapsed-comments-{{$thread.id}}" class="collapsed-comments" style="display: none;">
        {{/if}}
        {{if $item.comment_lastcollapsed}}</div>{{/if}}

        {{include file="{{$item.template}}"}}

    {{/foreach}}
</div>
{{/foreach}}

{{if !$update}}
<div id="conversation-end"></div>
{{if $dropping}}
    <button id="item-delete-selected" class="btn btn-primary" onclick="deleteCheckedItems();">
      <i id="item-delete-selected-icon" class="ri ri-delete-bin-line drophide"></i>
      <span>{{$dropping}}</span>
    </button>
{{/if}}
{{/if}}
