{{*
  * Copyright (C) 2010-2024, the Friendica project
  * SPDX-FileCopyrightText: 2010-2024 the Friendica project
  *
  * SPDX-License-Identifier: AGPL-3.0-or-later
  *}}

<script type="text/javascript" src="{{$baseurl}}/view/theme/smoothly/js/jquery.autogrow.textarea.js?v={{$smarty.const.FRIENDICA_VERSION}}"></script>
<script type="text/javascript">
function tautogrow(id) {
	$("textarea#comment-edit-text-" + id).autogrow();
}
</script>
