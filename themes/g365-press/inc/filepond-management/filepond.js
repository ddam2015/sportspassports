 window.addEventListener("DOMContentLoaded", function () {
      // initializing file pond js 
      FilePond.registerPlugin(
        FilePondPluginImageCrop,
        FilePondPluginImageTransform,
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginImageEdit,
        FilePondPluginFileValidateType,
        FilePondPluginFileValidateSize,
        FilePondPluginImageResize
      );
      // Select the file input and use 
      // create() to turn it into a pond
      FilePond.create(
        document.querySelector('#imagesFilepond'),
        {
          name: 'filepond',
          maxFiles: 5,
          allowBrowse: true,
          acceptedFileTypes: ['image/*'],
          allowFileSizeValidation: true,
          allowImageResize: true,
          imageTransformOutputQuality: 100,
//           imageCropAspectRatio: 1,
          imageResizeUpscale: false,
          imageResizeTargetWidth: null,
          imageResizeTargetHeight: 700,
          imageResizeMode: 'cover',
          // server
          server: {
            load: (uniqueFileId, load, error, progress, abort, headers) => {
              console.log('attempting to load', uniqueFileId);
              // implement logic to load file from server here
              // https://pqina.nl/filepond/docs/patterns/api/server/#load-1

              let controller = new AbortController();
              let signal = controller.signal;

              fetch(`load.php?key=${uniqueFileId}`, {
                method: "GET",
                signal,
              })
                .then(res => {
                  window.c = res
                  console.log(res)
                  return res.blob()
                })
                .then(blob => {
                  console.log(blob)
                  // const imageFileObj = new File([blob], `${uniqueFileId}.${blob.type.split('/')[1]}`, {
                  //   type: blob.type
                  // }) 
                  // console.log(imageFileObj)
                  // progress(true, size, size);
                  load(blob);
                })
                .catch(err => {
                  console.log(err)
                  error(err.message);
                })

              return {
                abort: () => {
                  // User tapped cancel, abort our ongoing actions here
                  controller.abort();
                  // Let FilePond know the request has been cancelled
                  abort();
                }
              };
            },
            // remove: 
          },
        }
      );

      FilePond.setOptions({
        server: {
          // url: "/",
          process: {
            url: '../../wp-content/themes/g365-press/inc/filepond-management/process.php',
            method: 'POST',
            headers: {
//               'x-customheader': 'Processing File'
            },
            onload: (response) => {
//               console.log("raw", response);
              response = JSON.parse(response);
//               console.log(response.key);
//               console.log(response.name);
//               location.reload(50);
              return response.name;
            },
            onerror: (response) => {
//               console.log("raw", response)
              response = JSON.parse(response);
//               console.log(response);
              return response.msg
            },
            ondata: (formData) => {
              window.h = formData;
//               console.log(formData)
              return formData;
            }
          },
//           revert: (uniqueFileId, load, error) => {
//             const formData = new FormData();
//             formData.append("key", uniqueFileId);

//             console.log(uniqueFileId);

//             fetch(`revert.php?key=${uniqueFileId}`, {
//               method: "DELETE",
//               body: formData,
//             })
//               .then(res => res.json())
//               .then(json => {
//                 console.log(json);
//                 if (json.status == "success") {
//                   // Should call the load method when done, no parameters required
//                   load();
//                 } else {
//                   // Can call the error method if something is wrong, should exit after
//                   error(err.msg);
//                 }
//               })
//               .catch(err => {
//                 console.log(err)
//                 // Can call the error method if something is wrong, should exit after
//                 error(err.message);
//               })
//           },
//           remove: (uniqueFileId, load, error) => {
//             const formData = new FormData();
//             formData.append("key", uniqueFileId);

//             console.log(uniqueFileId);

//             fetch(`revert.php?key=${uniqueFileId}`, {
//               method: "DELETE",
//               body: formData,
//             })
//               .then(res => res.json())
//               .then(json => {
//                 console.log(json);
//                 if (json.status == "success") {
//                   // Should call the load method when done, no parameters required
//                   load();
//                 } else {
//                   // Can call the error method if something is wrong, should exit after
//                   error(err.msg);
//                 }
//               })
//               .catch(err => {
//                 console.log(err)
//                 // Can call the error method if something is wrong, should exit after
//                 error(err.message);
//               })
//           },
//           restore: (uniqueFileId, load, error, progress, abort, headers) => {
//             let controller = new AbortController();
//             let signal = controller.signal;

//             fetch(`load.php?key=${uniqueFileId}`, {
//               method: "GET",
//               signal,
//             })
//               .then(res => {
//                 window.c = res
//                 console.log(res)
//                 const headers = res.headers;
//                 const contentLength = +headers.get("content-length");
//                 const contentDisposition = headers.get("content-disposition");
//                 let fileName = contentDisposition.split("filename=")[1];
//                 fileName = fileName.slice(1, fileName.length - 1)
//                 progress(true, contentLength, contentLength);
//                 return {
//                   blob: res.blob(),
//                   size: contentLength,
//                 }
//               })
//               .then(({ blob, size }) => {
//                 console.log(blob)
//                 // headersString = 'Content-Disposition: inline; filename="my-file.jpg"'
//                 // headers(headersString);

//                 const imageFileObj = new File([blob], `${uniqueFileId}.${blob.type.split('/')[1]}`, {
//                   type: blob.type
//                 })
//                 console.log(imageFileObj)
//                 progress(true, size, size);
//                 load(imageFileObj);
//               })
//               .catch(err => {
//                 console.log(err)
//                 error(err.message);
//               })

//             return {
//               abort: () => {
//                 // User tapped cancel, abort our ongoing actions here
//                 controller.abort();
//                 // Let FilePond know the request has been cancelled
//                 abort();
//               }
//             };
//           },
        },
      })
    })