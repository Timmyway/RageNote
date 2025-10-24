import axios from "axios";

// Helper method for handling errors
const handleError = (error: unknown): string => {
    if (axios.isAxiosError(error)) {
        // Check if it's an Axios error
        if (error.response?.status === 422) {
            // Handle validation errors
            console.log('===============> 422')
            return error.response.data.errors || 'Validation error occurred';
        }
        console.log('===============> 500')
        return error.response?.data?.message || 'Something went wrong';
    }
    // If it's not an Axios error, fallback to a generic error message
    return 'An unexpected error occurred';
}

export default handleError;
