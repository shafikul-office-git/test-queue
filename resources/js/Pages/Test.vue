<script setup>
import { ref, reactive, onMounted } from "vue";
import Dropzone from "dropzone";

const dropzoneRef = ref(null);
const uploadedFiles = reactive([]); // Uploaded files data
const uploadProgress = reactive({}); // Upload progress tracking

// Determine file preview type
const filePreviewType = (file) => {
  const fileType = file.type;
  const fileName = file.name.toLowerCase();

  if (fileType.startsWith("image/")) return "image";
  if (fileType === "application/pdf") return "pdf";
  if (fileName.endsWith(".xls") || fileName.endsWith(".xlsx")) return "excel";
  if (fileName.endsWith(".sql")) return "sql"; // SQL file support
  return "default"; // Default for unsupported files
};

onMounted(() => {
  const dropzone = new Dropzone(dropzoneRef.value, {
    url: "/file-upload",
    method: "post",
    headers: {
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
    },
    paramName: "file",
    maxFilesize: 30, // 30MB max size
    uploadMultiple: false,
    parallelUploads: 1,
    previewsContainer: null, // Disable previews in Dropzone
    init: function () {
      this.on("addedfile", (file) => {
        // Add file to the list
        uploadProgress[file.name] = 0;
        uploadedFiles.push({
          name: file.name,
          type: file.type,
          isUploading: true,
          previewType: filePreviewType(file),
          localUrl: URL.createObjectURL(file),
        });
      });

      this.on("uploadprogress", (file, progress) => {
        uploadProgress[file.name] = Math.round(progress);
      });

      this.on("success", (file, response) => {
        const fileIndex = uploadedFiles.findIndex((f) => f.name === file.name);
        if (fileIndex > -1) {
          uploadedFiles[fileIndex].isUploading = false;
          uploadedFiles[fileIndex].serverUrl = response.path; // Server path of the file
        }
        delete uploadProgress[file.name];
      });

      this.on("error", (file, errorMessage) => {
        const fileIndex = uploadedFiles.findIndex((f) => f.name === file.name);
        if (fileIndex > -1) {
          uploadedFiles[fileIndex].isUploading = false;
        }
        alert(`Error uploading ${file.name}: ${errorMessage}`);
      });
    },
  });
});
</script>

<template>
  <div class="container mx-auto p-4">
    <!-- Dropzone Area -->
    <div
      ref="dropzoneRef"
      class="dropzone-area border-4 border-dashed border-blue-500 rounded-lg p-6 flex items-center justify-center text-center bg-gray-50 cursor-pointer"
    >
      <p class="text-lg text-gray-600">Drag & Drop files here or click to upload</p>
    </div>

    <!-- Uploaded Files -->
    <div class="uploaded-files grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-8">
      <div
        v-for="(file, index) in uploadedFiles"
        :key="index"
        class="uploaded-file border rounded-lg p-4 bg-white shadow"
      >
        <div>
          <!-- Preview based on file type -->
          <img
            v-if="file.previewType === 'image'"
            :src="file.localUrl || file.serverUrl"
            :alt="file.name"
            class="file-preview object-cover w-full h-32 rounded"
          />
          <iframe
            v-else-if="file.previewType === 'pdf'"
            :src="file.localUrl || file.serverUrl"
            class="file-preview w-full h-32"
          ></iframe>
          <div
            v-else-if="file.previewType === 'excel'"
            class="file-preview flex flex-col items-center text-green-600"
          >
            <i class="fas fa-file-excel text-5xl"></i>
            <p class="text-sm mt-2">{{ file.name }}</p>
          </div>
          <div
            v-else-if="file.previewType === 'sql'"
            class="file-preview flex flex-col items-center text-blue-600"
          >
            <i class="fas fa-database text-5xl"></i>
            <p class="text-sm mt-2">{{ file.name }}</p>
          </div>
          <div v-else class="file-preview flex flex-col items-center text-gray-500">
            <i class="fas fa-file text-5xl"></i>
            <p class="text-sm mt-2">{{ file.name }}</p>
          </div>
        </div>

        <!-- Upload Progress -->
        <div v-if="file.isUploading" class="relative mt-2">
          <div class="h-2 bg-gray-200 rounded">
            <div
              class="h-full bg-blue-500 rounded transition-all"
              :style="{ width: uploadProgress[file.name] + '%' }"
            ></div>
          </div>
          <p class="text-sm text-gray-500 mt-2">{{ uploadProgress[file.name] }}% uploading...</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.dropzone-area {
  border-style: dashed;
  padding: 20px;
  background: #f9f9f9;
}

.uploaded-files {
  margin-top: 20px;
}

.file-preview {
  max-height: 120px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 10px;
}
</style>
