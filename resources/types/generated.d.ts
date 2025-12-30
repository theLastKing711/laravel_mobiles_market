declare namespace App.Data.Example {
export type ExampleCursorPaginationRequetData = {
data: Array<any>;
per_page: number;
next_cursor: string | null;
};
export type ExampleData = {
id: number;
ids: Array<any>;
};
}
declare namespace App.Data.Shared {
export type ListData = {
id: number;
title: string;
};
}
declare namespace App.Data.Shared.File {
export type CreateFilePathData = {
url: string;
public_id: string;
};
export type FilePathData = {
url: string;
};
export type ShowFileData = {
uid: number;
url: string;
};
export type UpdateFileData = {
uid: number | null;
url: string;
};
export type UploadFileData = {
file: any;
};
export type UploadFileResponseData = {
url: string;
public_id: string;
};
}
declare namespace App.Data.Shared.Media {
export type SingleMedia = {
id: string;
file_url: string;
};
}
declare namespace App.Enum {
export type Gender = 0 | 1;
}
declare namespace App.Enum.Auth {
export type RolesEnum = 'admin' | 'user' | 'driver' | 'store';
}
