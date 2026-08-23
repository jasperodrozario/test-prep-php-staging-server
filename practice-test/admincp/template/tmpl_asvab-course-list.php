<?
echo "<br>";
switch ($action) {
    case "course-list":
?>
        <div class="row">

            <form method='post' action='<?= $configs['admin_vpath'] ?>asvab-course-list?action=course-list'>
                <div class='col-md-4'>
                    <input type='text' name='search' class='form-control' placeholder='Enter Search Key'>
                </div>
                <div class='col-md-2'><input type='submit' name='search_btn' class='btn btn-success' value='Search' style='width: 100%;'></div>
            </form>

        </div>

        <hr>

        <table id="table-1" width="100%" class="table table-bordered table-striped" border="0" align="center" cellspacing="1" cellpadding="4">
            <thead>
                <tr>
                    <td align="center"><b>ID</b></td>
                    <td align="center"><b>Thumb Pic</b></td>
                    <td align="center"><b>Name</b></td>
                    <td align="center"><b>Status</b></td>
                    <td align="center"><b>Order</b></td>
                    <td width="20%" align="center"><b>Action</b></td>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($db_list as $d) {
                    if (!$d['thumb_pic']) {
                        $d['thumb_pic'] = "uploads/no-user.png";
                    }

                    $image_path = str_replace("practice-test/", "", $configs['vpath']);

                    echo "
            <tr>
                <td align='center'>" . $d['course_id'] . "</td>
                <td>
                    <img src='" . $configs['vpath'] . "image.php?src=" . $image_path . $d['thumb_pic'] . "&w=70'>
                </td>
                <td>" . ucwords($d['name']) . "</td>
                <td>" . $d['status'] . "</td>
                <td>" . $d['ord'] . "</td>
                <td align='center'> 
                    <a   href='asvab-course-list?action=add-course&course_id=" . $d['course_id'] . "' class='btn btn-info btn-sm'>Edit</a>  
                    <a   href=\"javascript:if(confirm('Are you sure you want to delete?')){window.location='asvab-course-list?action=course-list&del_course_id=" . $d['course_id'] . "';}\" class='btn btn-danger btn-sm'>Delete</a>
                </td>
            </tr>";
                }
                ?>
            </tbody>
        </table>

        <?
        break;

    case "add-course":
        $existing_variants = $existing_variants ?? [];
        $form_course_id = $_GET['course_id'] ?? ($db_value['course_id'] ?? '');
        ?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>asvab-course-list?action=add-course<?= $form_course_id ? '&course_id=' . (int)$form_course_id : '' ?>' enctype="multipart/form-data">
            <input type="hidden" name="course_id" value="<?php echo htmlspecialchars((string)$form_course_id); ?>">

            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>

                <tr>
                    <td align="left" width="400px">Course Name</td>
                    <td><input type="text" name="name" class='form-control' value="<?php echo $db_value['name']; ?>" required></td>
                </tr>

                <tr>
                    <td align="left">Header</td>
                    <td><input type="text" name="header" class='form-control' value="<?php echo $db_value['header']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Tagline</td>
                    <td><input type="text" name="tagline" class='form-control' value="<?php echo $db_value['tagline']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Details</td>
                    <td>
                        <textarea name="details" class="form-control" rows="5"><?php echo $db_value['details']; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td align="left">Curriculum</td>
                    <td>
                        <textarea name="curriculum" class="form-control" rows="5"><?php echo $db_value['curriculum']; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td align="left">Status</td>
                    <td>
                        <label>
                            <input type="radio" name="status" value="Y" <?php echo ($db_value['status'] == 'Y') ? 'checked' : ''; ?>> Active
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="radio" name="status" value="N" <?php echo ($db_value['status'] == 'N') ? 'checked' : ''; ?>> Inactive
                        </label>
                    </td>

                </tr>

                <tr>
                    <td align="left">Video Title</td>
                    <td><input type="text" name="video_title" class='form-control' value="<?php echo $db_value['video_title']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Video URL</td>
                    <td><input type="text" name="video" class='form-control' value="<?php echo $db_value['video']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Thumb Picture</td>
                    <td><input class="form-control" name="thumb_pic" type="file" accept=".png, .jpg, .jpeg"></td>
                </tr>

                <tr>
                    <td align="left">Course Name in Test Prep</td>
                    <td><input type="text" name="course_name_test_prep" class='form-control' value="<?php echo $db_value['course_name_test_prep']; ?>"></td>
                </tr>

                <tr>
                    <td align="left">Course Order</td>
                    <td><input type="number" name="ord" class='form-control' value="<?php echo $db_value['ord']; ?>"></td>
                </tr>

                <?php if ($form_course_id): ?>
                <tr>
                    <td align="left" style="vertical-align: top;">Course Variants (optional)</td>
                    <td>
                        <?php if (!empty($existing_variants)): ?>
                            <table class="table table-bordered table-condensed" style="margin-bottom: 12px;">
                                <thead>
                                    <tr>
                                        <th>Label</th>
                                        <th>Course Name</th>
                                        <th>Main Course ID</th>
                                        <th>Order</th>
                                        <th>Active</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($existing_variants as $ev): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($ev['label']) ?></td>
                                            <td><?= htmlspecialchars($ev['course_name']) ?></td>
                                            <td><?= (int)$ev['main_course_id'] ?></td>
                                            <td><?= (int)$ev['ord'] ?></td>
                                            <td><?= ((int)$ev['is_active'] === 1) ? 'Y' : 'N' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                        <a href="asvab-course-list?action=add-variant&course_id=<?= (int)$form_course_id ?>" class="btn btn-primary">Add Variant</a>
                    </td>
                </tr>
                <?php endif; ?>

                <tr>
                    <td align="left" style="vertical-align: top;">Lesson Plan</td>
                    <td>
                        <?php if (isset($sessions_list) && !empty($sessions_list)): ?>
                            <ul style="list-style: none; padding-left: 0;">
                                <?php foreach ($sessions_list as $session): ?>
                                    <li style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <strong>Session: <?= $session['session_title'] ?></strong>
                                            <div>
                                                <a href="asvab-course-list?action=add-session&id=<?= $session['id'] ?>&course_id=<?= $_GET['course_id'] ?>" class="btn btn-info btn-xs">Edit</a>
                                                <a href="javascript:if(confirm('Delete session and all its lessons?')){window.location='asvab-course-list?action=add-session&id=<?= $session['id'] ?>&course_id=<?= $_GET['course_id'] ?>&SBMT_DEL=1';}" class="btn btn-danger btn-xs">Delete</a>
                                            </div>
                                        </div>
                                        <?php if (!empty($session['lessons'])): ?>
                                            <ul style="margin-top: 10px;">
                                                <?php foreach ($session['lessons'] as $lesson): ?>
                                                    <li style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                                        <span><?= $lesson['lesson_name'] ?></span>
                                                        <div>
                                                            <a href="asvab-course-list?action=add-lesson&id=<?= $lesson['id'] ?>&session_id=<?= $session['id'] ?>" class="btn btn-default btn-xs">Edit</a>
                                                            <a href="javascript:if(confirm('Delete lesson?')){window.location='asvab-course-list?action=add-lesson&id=<?= $lesson['id'] ?>&session_id=<?= $session['id'] ?>&SBMT_DEL=1';}" class="btn btn-danger btn-xs">Delete</a>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                        <div style="margin-top: 10px;">
                                            <a href="asvab-course-list?action=add-lesson&session_id=<?= $session['id'] ?>" class="btn btn-success btn-xs">Add Lesson</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <a href="asvab-course-list?action=add-session&course_id=<?= $_GET['course_id'] ?>" class="btn btn-primary">Add Session</a>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' name='submit' value='Save' class='btn btn-success'>&nbsp;&nbsp;
                        <? if ($id) { ?>
                            <input type='submit' name='SBMT_DEL' class='btn btn-danger' value='Delete'>
                        <? } ?>
                    </td>
                </tr>
            </table>
        </form>
    <?
        break;
    case "add-variant":
        $variant_course_id = (int)($course_id ?? $_GET['course_id'] ?? 0);
        $selected_group_id = (int)($db_value['group_id'] ?? 0);
        ?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>asvab-course-list?action=add-variant&course_id=<?= $variant_course_id ?>' id="variant-form" novalidate>
            <input type="hidden" name="course_id" value="<?= $variant_course_id ?>">
            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
                <tr>
                    <td align="left" width="400px">Parent Course</td>
                    <td><?= htmlspecialchars($parent_course['name'] ?? '') ?></td>
                </tr>
                <tr>
                    <td align="left">Label <span class="text-danger">*</span></td>
                    <td>
                        <input type="text" name="label" class="form-control" value="<?= htmlspecialchars($db_value['label'] ?? '') ?>" placeholder="e.g. Grade 5" required>
                    </td>
                </tr>
                <tr>
                    <td align="left">Name <span class="text-danger">*</span></td>
                    <td>
                        <input type="text" name="course_name" class="form-control" value="<?= htmlspecialchars($db_value['course_name'] ?? '') ?>" required>
                    </td>
                </tr>
                <tr>
                    <td align="left">Description</td>
                    <td>
                        <textarea class="form-control description" name="description" rows="4"><?= html_entity_decode($db_value['description'] ?? '') ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="left">Display Order <span class="text-danger">*</span></td>
                    <td>
                        <input type="number" name="ord" class="form-control" value="<?= htmlspecialchars((string)($db_value['ord'] ?? '0')) ?>" required style="max-width: 120px;">
                    </td>
                </tr>
                <tr>
                    <td align="left">Course Group <span class="text-danger">*</span></td>
                    <td>
                        <select name="group_id" class="form-control" required>
                            <option value="">Select Course Group</option>
                            <?php
                            $course_group = getQuery("course_group", "*", "", "group_name");
                            foreach ($course_group as $dd) {
                                $gid = (int)$dd['group_id'];
                                $selected = ($selected_group_id === $gid) ? ' selected' : '';
                                echo '<option value="' . $gid . '"' . $selected . '>' . htmlspecialchars($dd['group_name']) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="left">Options</td>
                    <td>
                        <label style="margin-right: 16px;">
                            <input type="checkbox" name="is_online" value="1" <?= isset($db_value['is_online']) ? 'checked' : '' ?>> Is Online
                        </label>
                        <label style="margin-right: 16px;">
                            <input type="checkbox" name="app" value="1" <?= !empty($db_value['app']) ? 'checked' : '' ?>> App
                        </label>
                        <label style="margin-right: 16px;">
                            <input type="checkbox" name="tutoring" value="1" <?= !empty($db_value['tutoring']) ? 'checked' : '' ?>> Tutoring
                        </label>
                        <label>
                            <input type="checkbox" name="subscribe" value="1" <?= !empty($db_value['subscribe']) ? 'checked' : '' ?>> Subscribe
                        </label>
                    </td>
                </tr>
            </table>
            <div style="text-align: center;">
                <input type="submit" name="submit" value="Save" class="btn btn-success">&nbsp;&nbsp;
                <a href="asvab-course-list?action=add-course&course_id=<?= $variant_course_id ?>" class="btn btn-default">Back To Course</a>
            </div>
        </form>
        <script>
            (function() {
                var form = document.getElementById('variant-form');
                if (!form) return;

                form.addEventListener('submit', function(e) {
                    var label = form.querySelector('[name="label"]');
                    var courseName = form.querySelector('[name="course_name"]');
                    var ord = form.querySelector('[name="ord"]');
                    var groupId = form.querySelector('[name="group_id"]');
                    var errors = [];

                    if (!label.value.trim()) {
                        errors.push('Label is required.');
                        label.setCustomValidity('Label is required.');
                    } else {
                        label.setCustomValidity('');
                    }

                    if (!courseName.value.trim()) {
                        errors.push('Name is required.');
                        courseName.setCustomValidity('Name is required.');
                    } else {
                        courseName.setCustomValidity('');
                    }

                    if (ord.value === '') {
                        errors.push('Display Order is required.');
                        ord.setCustomValidity('Display Order is required.');
                    } else {
                        ord.setCustomValidity('');
                    }

                    if (!groupId.value || groupId.value === '0') {
                        errors.push('Course Group is required.');
                        groupId.setCustomValidity('Course Group is required.');
                    } else {
                        groupId.setCustomValidity('');
                    }

                    if (errors.length) {
                        e.preventDefault();
                        alert(errors.join('\n'));
                        if (errors[0].indexOf('Label') === 0) label.focus();
                        else if (errors[0].indexOf('Name') === 0) courseName.focus();
                        else if (errors[0].indexOf('Display') === 0) ord.focus();
                        else groupId.focus();
                    }
                });
            })();
        </script>
    <?
        break;
    case "add-session":
    ?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>asvab-course-list?action=add-session&id=<?= $_GET['id'] ?>&course_id=<?= $_GET['course_id'] ?>' enctype="multipart/form-data">
            <input type="hidden" name="course_id" value="<?php echo $_GET['course_id'] ?? $db_value['course_id'] ?>">
            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
                <tr>
                    <td align="left">Session Title</td>
                    <td><input type="text" name="session_title" class='form-control' value="<?php echo $db_value['session_title']; ?>" required></td>
                </tr>
                <tr>
                    <td align="left">Session Order</td>
                    <td><input type="number" name="order_index" class='form-control' value="<?php echo $db_value['order_index']; ?>" required></td>
                </tr>
            </table>
            <div style="text-align: center;">
                <input type='submit' name='submit' value='Save' class='btn btn-success'>&nbsp;&nbsp;
                <? if ($_GET['id']) { ?>
                    <input type='submit' name='SBMT_DEL' class='btn btn-danger' value='Delete'>
                <? } ?>
            </div>
        </form>
    <?
        break;  
    case "add-lesson":
    ?>
        <form method="post" action='<?= $configs['admin_vpath'] ?>asvab-course-list?action=add-lesson&id=<?= $_GET['id'] ?>&session_id=<?= $_GET['session_id'] ?>' enctype="multipart/form-data">
            <input type="hidden" name="session_id" value="<?php echo $_GET['session_id'] ?? $db_value['session_id'] ?>">
            <table width="100%" border="0" align="center" cellspacing="1" cellpadding="4" class='table table-bordered'>
                <tr>
                    <td align="left">Lesson Name</td>
                    <td><input type="text" name="lesson_name" class='form-control' value="<?php echo $db_value['lesson_name']; ?>" required></td>
                </tr>
                <tr>
                    <td align="left">Lesson Order</td>
                    <td><input type="number" name="order_index" class='form-control' value="<?php echo $db_value['order_index']; ?>" required></td>
                </tr>
            </table>
            <div style="text-align: center;">
                <input type='submit' name='submit' value='Save' class='btn btn-success'>&nbsp;&nbsp;
                <? if ($_GET['id']) { ?>
                    <input type='submit' name='SBMT_DEL' class='btn btn-danger' value='Delete'>
                <? } ?>
            </div>
        </form>
    <?
        break;  
    case "grant":
    ?>
        <form id='form1' method='post' action='<?= $configs['admin_vpath'] ?>tutor-list?action=grant&admin_id=<?php echo $_GET['admin_id'] ?>'>
            <input type='hidden' name='admin_id' value='<?php echo $_GET['admin_id'] ?>'>
            <input type='hidden' name='action' value='grant-access'>
            <table border=0 cellpadding=4 cellspacing=1 width=100% align="center" class='table table-bordered table-striped'>
                <tr>
                    <td height=30 align="left" colspan=2><b>Check/uncheck all</b> <input type='checkbox' name="checkall" id="checkall" onClick="check_uncheck_checkbox(this.checked);"> <input type='submit' name='SBMT_REG' class='btn btn-success pull-right' value='Update'>&nbsp;&nbsp;<input type="Reset" class='btn btn-danger pull-right'>&nbsp;&nbsp;</td>
                </tr>

                <?
                foreach ($menus as $d) {
                    echo '<tr ><td colspan="2"><b>' . $d['name'] . '</b></td></tr>';
                    $sub = $submenus[$d['id']];
                    if (count($sub)) {
                        foreach ($sub as $s) {
                            $chk = "";
                            if (in_array($s['id'], $grants)) {
                                $chk = "checked";
                            }
                ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td><input class='customcheckbox' type='checkbox' name='progid[]' value="<?php echo $s['id'] ?>,<?php echo $s['parent_id'] ?>" <?php echo $chk ?>> <?php echo $s['name']; ?></td>
                            </tr>
                <?
                        }
                    }
                }

                ?>

                <tr>
                    <td colspan=2 height=25 align=center><input type='submit' name="SBMT_REG" class='btn btn-ar btn-primary' value='Submit'></td>
                </tr>
            </table>
        </form>

<?
        break;
}
?>
<script>
    function check_uncheck_checkbox(isChecked) {
        if (isChecked) {
            $('.customcheckbox').each(function() {
                this.checked = true;
            });
        } else {
            $('.customcheckbox').each(function() {
                this.checked = false;
            });
        }
    }
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/latest.js?config=TeX-AMS_HTML' async></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    CKEDITOR.replace('details', {
        extraPlugins: 'mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
        height: 120,
        filebrowserUploadUrl: 'application/ck_upload.php',
        filebrowserUploadMethod: 'form',
        versionCheck: false
    });

    CKEDITOR.replace('curriculum', {
        extraPlugins: 'mathjax',
        mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML',
        height: 80,
        filebrowserUploadUrl: 'application/ck_upload.php',
        filebrowserUploadMethod: 'form',
        versionCheck: false
    });

    if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
        document.getElementById('ie8-warning').className = 'tip alert';
    }
</script>

<style>
    .link {
        font-size: 13px;
        /* Increase font size */
        font-weight: bold;
    }
</style>