{{*
  * Copyright (C) 2010-2024, the Friendica project
  * SPDX-FileCopyrightText: 2010-2024 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}

<div class="hide-comments-outer">
<span id="hide-comments-total-{{$id}}" class="hide-comments-total">{{$num_comments}}</span> <span id="hide-comments-{{$id}}" class="hide-comments fakelink" onclick="showHideComments({{$id}});">{{$hide_text}}</span>
</div>
<div id="collapsed-comments-{{$id}}" class="collapsed-comments" style="display: {{$display}};">
