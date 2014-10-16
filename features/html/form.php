<?php

    $fields = [];

    for ($i=0;$i<5;$i++) {
        $field = [
            'id' => sprintf('first-radio-%s', $i),
            'label' => sprintf('Radio field %s', $i),
            'type' => 'radio',
            'name' => 'first-radio',
        ];
        $fields[] = $field;
    }

    for ($i=0;$i<3;$i++) {
        $field = [
            'id' => sprintf('second-radio-%s', $i),
            'label' => sprintf('Radio field %s', $i),
            'type' => 'radio',
            'name' => 'second-radio',
        ];
        $fields[] = $field;
    }

    for ($i=0;$i<5;$i++) {
        $field = [
            'id' => sprintf('text-%s', $i),
            'label' => 'Text field',
            'type' => 'text',
            'name' => sprintf('text-%s', $i),
        ];
        $fields[] = $field;
    }
?>
<html>
    <head>
        <script src="jquery.js">
        </script>
    </head>
    <body>
        <div></div>
        <form>
<?php
    foreach ($fields as $field) {
?>
    <label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label>
    <input id="<?php echo $field['id']; ?>" type="<?php echo $field['type']; ?>" name="<?php echo $field['name']; ?>" />
    <br />
<?php
    }
?>
        </form>
        <script>
            var change = function() {
                var str = 'Field ' + $(this).prev('label').text() + ' (' + $(this).attr('name') + ') value is ' + $(this).val();
                $('div').text(str);
            };
            $('input').change(change);
            $('input').keyup(change);
        </script>
    </body>
</html>
