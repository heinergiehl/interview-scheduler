export type User = {
    id: number;
    name: string;
    email: string;
    is_applicant: boolean;
};
export type Suggestion = {
    id: number;
    employer_id: number;
    candidate_id: number;
    suggested_date_time: string;
    responded_at: string | null;
    appointment_status: 'pending' | 'accepted' | 'declined';
    user: User;
    canUpdate: boolean;
    canDelete: boolean;
};
