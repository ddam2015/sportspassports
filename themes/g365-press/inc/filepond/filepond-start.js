
// First register any plugins
$.fn.filepond.registerPlugin(FilePondPluginImagePreview);

// Turn input element into a pond with configuration options
$('.filepond').filepond({
	allowMultiple: true,
// 	maxFiles: true,
// 	maxFileSize: '3MB',
	server: { //A server configuration object describing how FilePond should interact with the server.
// 		url: 'https://media.grassroots365.com',
		url: 'https://media.grassroots365.com',
		process: {
// 			url: './process',
			url: '/player-photo-upload/',
			method: 'POST',
			withCredentials: false,
			headers: {},
			timeout: 7000,
			onload: null,
			onerror: null,
			ondata: null,
		},
	},
	chunkUploads: false, //Enable chunked uploads, when enabled will automatically cut up files in chunkSize chunks before upload
	chunkSize: 5000000, //The size of a chunk in bytes
	chunkRetryDelays: [500,1000,3000] //Amount of times, and delayes, between retried uploading of a chunk
});

// Turn input element into a pond
// get a reference to the input element
// const pondElement = document.querySelector('.filepond');

// create a FilePond instance at the input element location
// const pond = FilePond.create(document.querySelector('.filepond'), {
// 	allowMultiple: true,
// 	maxFiles: true,
// 	server: { //A server configuration object describing how FilePond should interact with the server.
// 		url: 'https://media.grassroots365.com',
// 		process: {
// 			url: './process',
// 			method: 'POST',
// 			withCredentials: false,
// 			headers: {},
// 			timeout: 7000,
// 			onload: null,
// 			onerror: null,
// 			ondata: null,
// 		},
// 	},
// 	chunkUploads: false, //Enable chunked uploads, when enabled will automatically cut up files in chunkSize chunks before upload
// 	chunkSize: 5000000, //The size of a chunk in bytes
// 	chunkRetryDelays: [500,1000,3000] //Amount of times, and delayes, between retried uploading of a chunk
// });
// console.log('hee', pondElement);


// Listen for addfile event
$('.filepond').on('FilePond:addfile', function(e) {
		console.log('file added event', e);
});

// Manually add a file using the addfile method
// $('.filepond').first().filepond('addFile').then(function(file){
// 	console.log('file added', file);
// });

