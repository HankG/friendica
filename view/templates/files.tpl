{{*
  * Copyright (C) 2010-2024, the Friendica project
  * SPDX-FileCopyrightText: 2010-2024 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}
{{foreach $items as $item }}
<p>{{$item.title}}  ({{$item.mime}}) ({{$item.filename}})</p>
{{/foreach}}
{{include "paginate.tpl"}}
