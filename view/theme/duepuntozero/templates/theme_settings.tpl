{{*
  * Copyright (C) 2010-2024, the Friendica project
  * SPDX-FileCopyrightText: 2010-2024 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}
<script src="{{$baseurl}}/view/theme/quattro/jquery.tools.min.js?v={{$smarty.const.FRIENDICA_VERSION}}"></script>

{{include file="field_select.tpl" field=$colorset}} 

<div class="settings-submit-wrapper">
	<input type="submit" value="{{$submit}}" class="settings-submit" name="duepuntozero-settings-submit" />
</div>

