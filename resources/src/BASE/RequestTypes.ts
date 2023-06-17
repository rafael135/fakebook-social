export type RequestType = {
    response: object | string,
    status: number
}

export type Post = {
    id: number,
    user_id: number,
    type: string,
    body: string,
    created_at: string,
    updated_at: string
}

export type User = {
    id: number,
    avatar: string | null,
    birth_date: string | null,
    city: string | null,
    country: string | null,
    cover: string | null,
    email: string,
    email_verified_at: string | null,
    friend_count: number,
    last_online_at: string | null,
    name: string,
    state: string | null,
    uniqueUrl: string,
    created_at: string | null,
    updated_at: string | null,
    deleted_at: string | null
}

export type PostType = {
    response: {
        post: Post,
        user: User | null,
    }
    status: number
}

export type PostLike = {
    id: number,
    post_id: number,
    user_id: number,
    created_at: string,
    updated_at: string
}

export type PostLikeType = {
    response: boolean | string | PostLike,
    status: number
}


export type FriendRelation = {
    id: number,
    user_from: number,
    user_to: number,
    created_at: string,
    updated_at: string
}

export type FriendRelationRequest = {
    response: boolean | FriendRelation,
    status: number
}