{{*
  * Copyright (C) 2010-2024, the Friendica project
  * SPDX-FileCopyrightText: 2010-2024 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}
<div class="imagegrid-row">
	<div class="imagegrid-column">
		{{foreach $columns.fc as $img}}
				{{include file="content/image/single.tpl" image=$img}}
		{{/foreach}}
	</div>
	<div class="imagegrid-column">
		{{foreach $columns.sc as $img}}
				{{include file="content/image/single.tpl" image=$img}}
		{{/foreach}}
	</div>
</div>
