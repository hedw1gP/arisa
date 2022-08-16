<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content= "width=device-width, user-scalable=no">
        <title>Arisa Project demo</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/@forevolve/bootstrap-dark@1.1.0/dist/css/bootstrap-light.min.css" />
        <link rel="stylesheet" href="https://fastly.jsdelivr.net/npm/@forevolve/bootstrap-dark@1.1.0/dist/css/bootstrap-prefers-dark.min.css" />
        </style>
    </head>
    <body style="min-height: 100vh; max-width: device-width;">
        <!-- 初始化数据库链接 !-->
        <?php
            $mysqli = new mysqli("127.0.0.1", "arisa", "", "hwpm");
            $mysqli->set_charset("utf8");
            $source = "https://arisaproject-1253370021.cos.ap-chengdu.myqcloud.com/";
            ?>
        <!-- PHP 函数 !-->
        <?php
            //获取游戏 Master 版本
            function getRemoteMasterVersion()
              {
                $url = "https://production.arisa-project.net/api/Master/Version";
                $options = array(
                    'http' => array(
                        'method' => 'GET',
                        'header' => "X-Chikuzen-Client-App-Platform: Ios\n" . "X-DeviceTime: 0\n" . "X-Chikuzen-Client-App-Version: 2.6.0\n"
                    )
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $json = json_decode($result, true);
                if (!isset($json["Code"]) || $json["Code"] == 3)
                  {
                    return "Under maintenance";
                  }
                return $json["MasterVersion"];
              }
            
            // 获取数据库中的 Master 版本
            function getLocalMasterVersion($mysqli)
              {
                $command = "SELECT * FROM character_version";
                $result = mysqli_query($mysqli, $command);
                $row = mysqli_fetch_array($result);
                return $row["MasterVersion"];
              }
            
            // 获取角色名称并输出为选项
            function getCharacters($mysqli)
              {
                $command = "SELECT * FROM character_characters";
                $result = mysqli_query($mysqli, $command);
                echo "";
                while ($row = mysqli_fetch_array($result))
                  {
                    echo "<option value='" . $row['Name'] . "'>" . $row['Name'] . "</option>";
                  }
              }
            function echoCharacterPic($mysqli)
              {
                global $source;
                // Card character
                $picture_char_link1 = $source . "Characters/" . $_GET["charactercards"] . "_1_sd";
                $picture_char_link2 = $source . "Characters/" . $_GET["charactercards"] . "_2_sd";
                $command = "SELECT * from character_charactercards WHERE Id = " . $_GET['charactercards'] . ";";
                $result = mysqli_query($mysqli, $command);
                $row = mysqli_fetch_array($result);
                echo "<strong>" . $row["Name"] . "</strong>";
                $command = "SELECT * from character_tags WHERE Id in (" . $row["TagIdBits"] . ");";
                $result = mysqli_query($mysqli, $command);
                while ($row = mysqli_fetch_array($result))
                  {
                    // 角色 Hashtags
                    echo "<span class='badge badge-pill badge-primary'>" . $row["Name"] . "</span>";
                  }
                echo "<br>";
                echo "<a href=\"$picture_char_link1.png\"><img src=\"$picture_char_link1.webp\" width='50%' /></a>";
                echo "<a href=\"$picture_char_link2.png\"><img src=\"$picture_char_link2.webp\" width='50%' /></a>";
                echo "<br>";
              }
            function echoCharacterSceneCards($mysqli)
              {
                global $source;
                $command = "SELECT * from character_charactercards WHERE Id = " . $_GET['charactercards'] . ";";
                $result = mysqli_query($mysqli, $command);
                $row = mysqli_fetch_array($result);
            
                $FirstSceneCardId = $row["FirstSceneCardId"];
                $RankUpSceneCardId = $row["RankUpSceneCardId"];
                $command = "SELECT ImageIdentifier from character_scenecards WHERE ItemId = " . $FirstSceneCardId . ";";
                $result = mysqli_query($mysqli, $command);
                // 有些基础角色没有 SceneCard，所以要设置一个条件
                if ($result == false)
                  {
                    echo "<p class='font-italic'>This character has no SceneCard. <br></p>";
                  }
                else
                  {
                    $row = mysqli_fetch_array($result);
                    $scene_card_id1 = $row["ImageIdentifier"];
                    $command = "SELECT ImageIdentifier from character_scenecards WHERE ItemId = " . $RankUpSceneCardId . ";";
                    $result = mysqli_query($mysqli, $command);
                    $row = mysqli_fetch_array($result);
                    $scene_card_id2 = $row["ImageIdentifier"];
                    $picture_card_link1 = $source . "SceneCards/" . $scene_card_id1;
                    $picture_card_link2 = $source . "SceneCards/" . $scene_card_id2;
            
                    echo "<a href=\"$picture_card_link1.png\"><img src=\"$picture_card_link1.webp\" width='50%' /></a>";
                    echo "<a href=\"$picture_card_link2.png\"><img src=\"$picture_card_link2.webp\" width='50%' /></a>";
                    echo "<br>";
                  }
              }
            function echoCharacterInfo($mysqli)
              {
                echo "<table class='table table-striped table-sm'>";
                $command = "SELECT * from character_characters WHERE Name = \"" . $_GET['character'] . "\";";
                $result = mysqli_query($mysqli, $command);
                $row = mysqli_fetch_array($result);
                echo "<tr><th style='text-align:center' colspan='2'><a>" . $row["Name"] . "</a></th></tr>";
                echo "<tr>";
                echo "<th>VoiceActor</th> <td>" . $row["VoiceActor"] . "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th>Height</th> <td>" . $row["Height"] . "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th>Birthday</th> <td>" . $row["Birthday"] . " " . $row["Constellation"] . "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th>BloodType</th> <td>" . $row["BloodType"] . "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th>Description</th> <td>" . $row["Description"] . "</td>";
                echo "</tr>";
                echo "</table>";
              }
            ?>
        <!-- 正文开始 !-->
        <form>
            <!-- 角色选项 !-->
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="characterSelect">Character</label>
                </div>
                <select name='character' class='custom-select' id="characterSelect">
                    <option selected>Select...</option>
                    <?php
                        getCharacters($mysqli)
                        ?>
                </select>
            </div>
            <!-- 卡牌选项 !-->
            <div class="input-group">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="cardSelect">Card</label>
                </div>
                <select name="charactercards" class="custom-select">
                    <option selected>-----------</option>
                </select>
            </div>
            <br>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form>
        <?php
            if (isset($_GET["charactercards"]))
              {
                echoCharacterPic($mysqli);
                echoCharacterSceneCards($mysqli);
              }
            
            // Character Info
            if (isset($_GET["character"]))
              {
                echoCharacterInfo($mysqli);
              }
            ?>
        <!-- Copyright -->
        <footer class="bg-light text-left text-lg-start">
            <div class="p-3 mb-2 bg-secondary text-white">
                <?php
                    echo "<small>Local MasterVersion: " . getLocalMasterVersion($mysqli) . "</small><br>";
                    echo "<small>Game MasterVersion: " . getRemoteMasterVersion() . "</small><br>";
                    ?>
                ©INCS toenter Co.,ltd/ MusicRay’n Inc. This demo is not affiliated with HoneyWorks Premium Live.
            </div>
        </footer>
        <!-- 动态刷新卡牌选择 !-->
        <script>
            $("select[name='character']").change(function () {
                var characterName = $(this).val();
                if(characterName) {
                    $('select[name="charactercards"]').empty();
                    $('select[name="charactercards"]').append('<option value="loading">Loading...</option>');
                    $.ajax({
                        url: "ajax_character.php",
                        dataType: 'Json',
                        data: {'characterName':characterName},
                        success: function(data) {
                            $('select[name="charactercards"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="charactercards"]').append('<option value="'+ key +'">'+ value +'</option>');
                            });
                        }
                    });
                }else{
                    $('select[name="charactercards"]').empty();
                }
            });
        </script>
    </body>
</html>
