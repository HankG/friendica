{{*
  * Copyright (C) 2010-2024, the Friendica project
  * SPDX-FileCopyrightText: 2010-2024 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}
<a class="embed_video" href="{{$embedurl}}" onclick="this.innerHTML=window.atob('{{$escapedhtml}}'); this.classList.add('active'); return false;">
	<img width="{{$tw}}" height="{{$th}}" src="{{$turl}}">
	<div style="width: {{$tw}}px; height: {{$th}}px;"></div>
</a>
