<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VirusTotal Clone</title>
</head>
<body>
    <h1>Welcome to VirusTotal Clone</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        Select file to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload File" name="submit">
    </form>
    <form action="search.php" method="get">
        Search by Hash:
        <input type="text" name="hash" placeholder="md5, sha1, or sha256">
        <input type="submit" value="Search">
    </form>
</body>
</html>
