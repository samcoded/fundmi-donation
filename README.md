# fundmi-donation

FundMi is platform to request for donations. create a donation and share link for others to donate to you.

### Live demo

### Tools

Built with Laravel 9 and Paystack for Payment

### Migrations

To create all the nessesary tables and columns, run the following

```
php artisan migrate
```

### File Uploading

When uploading listing files, they go to "storage/app/public". Create a symlink with the following command to make them publicly accessible.

```
php artisan storage:link
```

### Running Then App

Upload the files to your document root

```
php artisan serve
```

### Author

Samuel Anozie
