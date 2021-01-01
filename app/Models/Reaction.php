<?php

namespace App\Models;

use Cog\Contracts\Love\Reactant\Models\Reactant;
use Cog\Contracts\Love\Reacter\Models\Reacter;
use Cog\Contracts\Love\ReactionType\Models\ReactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model implements \Cog\Contracts\Love\Reaction\Models\Reaction
{
    use HasFactory;

    /**
     * @return string
     */
    public function getId(): string
    {
        // TODO: Implement getId() method.
    }

    /**
     * @return Reactant
     */
    public function getReactant(): Reactant
    {
        // TODO: Implement getReactant() method.
    }

    /**
     * @return Reacter
     */
    public function getReacter(): Reacter
    {
        // TODO: Implement getReacter() method.
    }

    /**
     * @return ReactionType
     */
    public function getType(): ReactionType
    {
        // TODO: Implement getType() method.
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        // TODO: Implement getRate() method.
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        // TODO: Implement getWeight() method.
    }

    /**
     * @param ReactionType $reactionType
     * @return bool
     */
    public function isOfType(ReactionType $reactionType): bool
    {
        // TODO: Implement isOfType() method.
    }

    /**
     * @param ReactionType $reactionType
     * @return bool
     */
    public function isNotOfType(ReactionType $reactionType): bool
    {
        // TODO: Implement isNotOfType() method.
    }

    /**
     * @param Reactant $reactant
     * @return bool
     */
    public function isToReactant(Reactant $reactant): bool
    {
        // TODO: Implement isToReactant() method.
    }

    /**
     * @param Reactant $reactant
     * @return bool
     */
    public function isNotToReactant(Reactant $reactant): bool
    {
        // TODO: Implement isNotToReactant() method.
    }

    /**
     * @param Reacter $reacter
     * @return bool
     */
    public function isByReacter(Reacter $reacter): bool
    {
        // TODO: Implement isByReacter() method.
    }

    /**
     * @param Reacter $reacter
     * @return bool
     */
    public function isNotByReacter(Reacter $reacter): bool
    {
        // TODO: Implement isNotByReacter() method.
    }

    /**
     * @param float $rate
     */
    public function changeRate(float $rate): void
    {
        // TODO: Implement changeRate() method.
    }
}
